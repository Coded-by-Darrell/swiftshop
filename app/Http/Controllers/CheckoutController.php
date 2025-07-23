<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index(Request $request)
    {
        // Get cart from session
        $cart = session()->get('cart', []);
        
        // If cart is empty, redirect to browse
        if (empty($cart)) {
            return redirect()->route('test.browse')->with('error', 'Your cart is empty');
        }
        
        // Group cart items by vendor for multi-vendor display
        $cartByVendor = $this->groupCartByVendor($cart);
        
        // Calculate totals
        $cartTotals = $this->calculateCartTotals($cart);
        
        // User account data (following your pattern)
        $userAccount = [
            'firstName' => 'Darrell',
            'lastName' => 'Ocampo',
            'fullName' => 'Darrell Ocampo'
        ];
        
        // Get saved addresses (mock data for now)
        $savedAddresses = $this->getSavedAddresses();
        
        return view('checkout.index', compact(
            'cartByVendor',
            'cartTotals',
            'userAccount',
            'savedAddresses'
        ));
    }
    
    /**
 * Process checkout and create orders
 */
public function store(Request $request)
{
    $request->validate([
        'shipping_method' => 'required|string|in:standard,express,same_day',
        'delivery_address' => 'nullable|string',
        'contact_email' => 'required|email',
        'phone_number' => 'required|string',
        'full_name' => 'required|string',
        'street_address' => 'required|string',
        'city' => 'required|string',
        'postal_code' => 'required|string',
        'order_notes' => 'nullable|string|max:500'
    ]);
    
    // Get cart from session
    $cart = session()->get('cart', []);
    
    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Cart is empty'
        ]);
    }
    
    try {
        // Start database transaction
        DB::beginTransaction();
        
        // Generate unique order number
        $orderNumber = Order::generateOrderNumber();
        
        // Calculate shipping fee based on method
        $shippingFees = [
            'standard' => 95,
            'express' => 195,
            'same_day' => 295
        ];
        $shippingFee = $shippingFees[$request->shipping_method];
        
        // Calculate totals
        $cartTotals = $this->calculateCartTotals($cart, $shippingFee);
        
        // Prepare shipping address
        $shippingAddress = [
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'email' => $request->contact_email,
            'street_address' => $request->street_address,
            'city' => $request->city,
            'postal_code' => $request->postal_code
        ];
        
        // Calculate estimated delivery
        $estimatedDelivery = $this->calculateEstimatedDelivery($request->shipping_method);
        
        // Create main order
        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->full_name,
            'customer_email' => $request->contact_email,
            'customer_phone' => $request->phone_number,
            'shipping_address' => $shippingAddress,
            'shipping_method' => $request->shipping_method,
            'order_notes' => $request->order_notes,
            'subtotal' => $cartTotals['subtotal'],
            'shipping_fee' => $cartTotals['shipping'],
            'tax_amount' => $cartTotals['tax'],
            'total_amount' => $cartTotals['total'],
            'payment_method' => 'COD',
            'status' => 'pending',
            'estimated_delivery' => $estimatedDelivery
        ]);
        
        // Create order items and update inventory
        $this->createOrderItems($order, $cart);
        
        // Update product stock
        $this->updateProductStock($cart);
        
        // Store order data for confirmation page
        $orderData = $this->prepareOrderConfirmationData($order, $cart);
        session()->put('last_order', $orderData);
        
        // Clear cart
        session()->forget('cart');
        
        // Commit transaction
        DB::commit();
        
        // Send order confirmation email (you can implement this later)
        // $this->sendOrderConfirmationEmail($order);
        
        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_number' => $orderNumber,
            'order_id' => $order->id,
            'redirect_url' => route('test.checkout.confirmation')
        ]);
        
    } catch (\Exception $e) {
        // Rollback transaction
        DB::rollback();
        
        Log::error('Order creation failed: ' . $e->getMessage(), [
            'cart' => $cart,
            'request' => $request->all()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to process order. Please try again.'
        ]);
    }
}

/**
 * Create order items for multi-vendor orders
 */
private function createOrderItems(Order $order, array $cart)
{
    foreach ($cart as $cartItem) {
        // Get product for stock verification and snapshot
        $product = Product::with(['vendor', 'category'])->find($cartItem['product_id']);
        
        if (!$product) {
            throw new \Exception("Product not found: {$cartItem['product_id']}");
        }
        
        // Verify stock availability
        if ($product->getTotalStock() < $cartItem['quantity']) {
            throw new \Exception("Insufficient stock for product: {$product->name}");
        }
        
        // Create product snapshot for order history
        $productSnapshot = [
            'product_id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'category' => $product->category->name,
            'vendor' => $product->vendor->business_name,
            'variant_id' => $cartItem['variant_id'],
            'images' => $product->images,
            'ordered_at' => now()->toDateTimeString()
        ];
        
        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $cartItem['product_id'],
            'vendor_id' => $cartItem['vendor_id'],
            'variant_id' => $cartItem['variant_id'],
            'product_name' => $cartItem['product_name'],
            'vendor_name' => $cartItem['vendor_name'],
            'unit_price' => $cartItem['price'],
            'quantity' => $cartItem['quantity'],
            'total_price' => $cartItem['price'] * $cartItem['quantity'],
            'product_snapshot' => $productSnapshot,
            'status' => 'pending'
        ]);
    }
}

/**
 * Update product stock after order
 */
private function updateProductStock(array $cart)
{
    foreach ($cart as $cartItem) {
        $product = Product::find($cartItem['product_id']);
        
        if ($product) {
            // If product has variants, update variant stock
            if ($cartItem['variant_id'] && $product->variants()->count() > 0) {
                $variant = $product->variants()->find($cartItem['variant_id']);
                if ($variant && $variant->stock_quantity >= $cartItem['quantity']) {
                    $variant->decrement('stock_quantity', $cartItem['quantity']);
                }
            } else {
                // Update main product stock
                if ($product->stock_quantity >= $cartItem['quantity']) {
                    $product->decrement('stock_quantity', $cartItem['quantity']);
                }
            }
        }
    }
}

/**
 * Calculate estimated delivery date
 */
private function calculateEstimatedDelivery($shippingMethod)
{
    $days = match($shippingMethod) {
        'same_day' => 0,
        'express' => 2,
        'standard' => 5,
        default => 5
    };
    
    return now()->addDays($days);
}

/**
 * Prepare order data for confirmation page
 */
private function prepareOrderConfirmationData(Order $order, array $cart)
{
    return [
        'order_id' => $order->id,
        'order_number' => $order->order_number,
        'customer_name' => $order->customer_name,
        'customer_email' => $order->customer_email,
        'customer_phone' => $order->customer_phone,
        'shipping_address' => $order->shipping_address,
        'shipping_method' => $order->shipping_method,
        'order_notes' => $order->order_notes,
        'subtotal' => $order->subtotal,
        'shipping_fee' => $order->shipping_fee,
        'tax_amount' => $order->tax_amount,
        'total_amount' => $order->total_amount,
        'payment_method' => $order->payment_method,
        'status' => $order->status,
        'estimated_delivery' => $order->estimated_delivery->format('M d, Y'),
        'created_at' => $order->created_at->format('M d, Y h:i A'),
        'cart_items' => $cart,
        'vendor_groups' => $this->groupCartByVendor($cart)
    ];
}
    
    /**
     * Show order confirmation page
     */
    public function confirmation()
    {
        // Get order data from session
        $orderData = session()->get('last_order');
        
        if (!$orderData) {
            return redirect()->route('test.browse')->with('error', 'No order found');
        }
        
        // User account data
        $userAccount = [
            'firstName' => 'Darrell',
            'lastName' => 'Ocampo',
            'fullName' => 'Darrell Ocampo'
        ];
        
        return view('checkout.confirmation', compact('orderData', 'userAccount'));
    }
    
    /**
     * Private helper methods
     */
    
    /**
     * Group cart items by vendor
     */
    private function groupCartByVendor($cart)
    {
        $grouped = [];
        
        foreach ($cart as $key => $item) {
            $vendorId = $item['vendor_id'];
            
            if (!isset($grouped[$vendorId])) {
                $grouped[$vendorId] = [
                    'vendor_name' => $item['vendor_name'],
                    'vendor_id' => $vendorId,
                    'items' => [],
                    'subtotal' => 0
                ];
            }
            
            $item['cart_key'] = $key;
            $item['total'] = $item['price'] * $item['quantity'];
            $grouped[$vendorId]['items'][] = $item;
            $grouped[$vendorId]['subtotal'] += $item['total'];
        }
        
        return $grouped;
    }
    
    

    /**
 * Save new address for user
 */
public function saveAddress(Request $request)
{
    $request->validate([
        'label' => 'required|string|max:50',
        'full_name' => 'required|string|max:100',
        'phone_number' => 'required|string|max:20',
        'street_address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'postal_code' => 'required|string|max:10',
        'is_default' => 'boolean'
    ]);
    
    // Get current saved addresses from session (simulating database)
    $savedAddresses = session()->get('user_addresses', []);
    
    // If setting as default, unset other defaults
    if ($request->is_default) {
        foreach ($savedAddresses as &$address) {
            $address['is_default'] = false;
        }
    }
    
    // Create new address
    $newAddress = [
        'id' => count($savedAddresses) + 1,
        'label' => $request->label,
        'full_name' => $request->full_name,
        'phone_number' => $request->phone_number,
        'street_address' => $request->street_address,
        'city' => $request->city,
        'postal_code' => $request->postal_code,
        'is_default' => $request->is_default ?? false
    ];
    
    // Add to saved addresses
    $savedAddresses[] = $newAddress;
    
    // Save to session
    session()->put('user_addresses', $savedAddresses);
    
    return response()->json([
        'success' => true,
        'message' => 'Address saved successfully!',
        'address' => $newAddress
    ]);
}

/**
 * Update shipping calculation based on method
 */
public function updateShipping(Request $request)
{
    $request->validate([
        'shipping_method' => 'required|string|in:standard,express,same_day'
    ]);
    
    // Get cart from session
    $cart = session()->get('cart', []);
    
    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Cart is empty'
        ]);
    }
    
    // Calculate shipping fee based on method
    $shippingFees = [
        'standard' => 95,
        'express' => 195,
        'same_day' => 295
    ];
    
    $shippingFee = $shippingFees[$request->shipping_method];
    
    // Recalculate totals
    $cartTotals = $this->calculateCartTotals($cart, $shippingFee);
    
    return response()->json([
        'success' => true,
        'shipping_fee' => $shippingFee,
        'cart_totals' => $cartTotals
    ]);
}

/**
 * Get user's saved addresses
 */
public function getAddresses()
{
    $savedAddresses = session()->get('user_addresses', $this->getSavedAddresses());
    
    return response()->json([
        'success' => true,
        'addresses' => $savedAddresses
    ]);
}

/**
 * Update calculateCartTotals to accept custom shipping
 */
private function calculateCartTotals($cart, $customShipping = null)
{
    $subtotal = 0;
    $totalItems = 0;
    
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
        $totalItems += $item['quantity'];
    }
    
    $shipping = $customShipping ?? 95; // Use custom shipping or default
    $tax = $subtotal * 0.12; // 12% VAT
    $total = $subtotal + $shipping + $tax;
    
    return [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'tax' => $tax,
        'total' => $total,
        'total_items' => $totalItems
    ];
}

/**
 * Enhanced getSavedAddresses with session integration
 */
private function getSavedAddresses()
{
    // Check if user has saved addresses in session
    $sessionAddresses = session()->get('user_addresses');
    
    if ($sessionAddresses && !empty($sessionAddresses)) {
        return $sessionAddresses;
    }
    
    // Return default addresses if no saved addresses
    return [
        [
            'id' => 1,
            'label' => 'Home',
            'full_name' => 'Darrell Ocampo',
            'phone_number' => '+63 917 123 4567',
            'street_address' => '123 Main Street, Barangay San Antonio',
            'city' => 'Bauan',
            'postal_code' => '4201',
            'is_default' => true
        ],
        [
            'id' => 2,
            'label' => 'Office',
            'full_name' => 'Darrell Ocampo',
            'phone_number' => '+63 917 123 4567',
            'street_address' => '456 Business Ave, Barangay Poblacion',
            'city' => 'Bauan',
            'postal_code' => '4201',
            'is_default' => false
        ]
    ];
}
    
}