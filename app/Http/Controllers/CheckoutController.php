<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\ProductVariant;

class CheckoutController extends Controller
{

    /**
 * Display the checkout page
 */
public function index(Request $request)
{
    // Check session type and get appropriate cart
    $isQuickCheckout = session()->get('is_quick_checkout', false);
    $isVendorCheckout = session()->get('is_vendor_checkout', false);
    $checkoutCart = session()->get('checkout_cart');
    
    if ($isQuickCheckout) {
        // Quick checkout (Buy Now)
        $cart = session()->get('quick_checkout_cart', []);
    } elseif ($checkoutCart) {
        // Cart checkout (vendor or all items)
        $cart = $checkoutCart;
    } else {
        // Regular cart checkout (fallback)
        $cart = session()->get('cart', []);
    }
    
    // If cart is empty, redirect to browse
    if (empty($cart)) {
        return redirect()->route('test.browse')->with('error', 'Your cart is empty');
    }
    
    // Group cart items by vendor for multi-vendor display
    $cartByVendor = $this->groupCartByVendor($cart);
    
    // Calculate totals
    $cartTotals = $this->calculateCartTotals($cart);
    
    // User account data
    $userAccount = Auth::user();
    
    // Get saved addresses
    $savedAddresses = $this->getSavedAddresses();
    
    // Determine checkout type for display
    $checkoutType = $isQuickCheckout ? 'quick' : ($isVendorCheckout ? 'vendor' : 'cart');
    
    return view('checkout.index', compact(
        'cartByVendor',
        'cartTotals',
        'userAccount',
        'savedAddresses',
        'isQuickCheckout',
        'checkoutType'
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
        'phone_number' => 'required|string',
        'full_name' => 'required|string',
        'street_address' => 'required|string',
        'city' => 'required|string',
        'postal_code' => 'required|string',
        'order_notes' => 'nullable|string|max:500'
    ]);
    
    // Get the appropriate cart based on session
    $isQuickCheckout = session()->get('is_quick_checkout', false);
    $checkoutCart = session()->get('checkout_cart');
    
    if ($isQuickCheckout) {
        // Quick checkout (Buy Now)
        $cart = session()->get('quick_checkout_cart', []);
    } elseif ($checkoutCart) {
        // Cart checkout (vendor or all items)
        $cart = $checkoutCart;
    } else {
        // Regular cart checkout (fallback)
        $cart = session()->get('cart', []);
    }
    
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
            'street_address' => $request->street_address,
            'city' => $request->city,
            'postal_code' => $request->postal_code
        ];
        
        // Calculate estimated delivery
        $estimatedDelivery = $this->calculateEstimatedDelivery($request->shipping_method);
        
        // Create main order
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => Auth::user()->id,
            'customer_name' => $request->full_name,
            'customer_email' => Auth::user()->email,
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
        
        // Clear the appropriate cart sessions
        if ($isQuickCheckout) {
            session()->forget(['quick_checkout_cart', 'is_quick_checkout']);
        } elseif ($checkoutCart) {
            // Remove checked out items from main cart if it was a partial checkout
            $this->removeCheckedOutItemsFromCart($cart);
            session()->forget(['checkout_cart', 'is_vendor_checkout', 'checkout_vendor_id']);
        } else {
            session()->forget('cart');
        }
        
        // Commit transaction
        DB::commit();
        
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
 * Remove checked out items from main cart
 */
private function removeCheckedOutItemsFromCart($checkedOutCart)
{
    $mainCart = session()->get('cart', []);
    
    foreach ($checkedOutCart as $cartKey => $item) {
        if (isset($mainCart[$cartKey])) {
            unset($mainCart[$cartKey]);
        }
    }
    
    session()->put('cart', $mainCart);
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
        $userAccount = Auth::user();
        
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
        'email' => 'required|email|max:100', // Make email required for new addresses
        'street_address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'postal_code' => 'required|string|max:10',
        'is_default' => 'nullable|boolean'
    ]);
    
    // Get current saved addresses from session
    $savedAddresses = session()->get('user_addresses', []);
    
    // Convert is_default to boolean properly
    $isDefault = $request->boolean('is_default');
    
    // If setting as default, unset other defaults
    if ($isDefault) {
        foreach ($savedAddresses as &$address) {
            $address['is_default'] = false;
        }
    }
    
    // Create new address (include email)
    $newAddress = [
        'id' => count($savedAddresses) + 1,
        'label' => $request->label,
        'full_name' => $request->full_name,
        'phone_number' => $request->phone_number,
        'email' => $request->email, // Include email in saved address
        'street_address' => $request->street_address,
        'city' => $request->city,
        'postal_code' => $request->postal_code,
        'is_default' => $isDefault
    ];
    
    // Add to saved addresses
    $savedAddresses[] = $newAddress;
    
    // Save to session
    session()->put('user_addresses', $savedAddresses);
    
    return response()->json([
        'success' => true,
        'message' => 'Address saved successfully!',
        'address' => $newAddress // This now includes the email
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
 * Enhanced getSavedAddresses with session integration and email
 */
private function getSavedAddresses()
{
    // Check if user has saved addresses in session
    $sessionAddresses = session()->get('user_addresses');
    
    if ($sessionAddresses && !empty($sessionAddresses)) {
        return $sessionAddresses;
    }
    
    // If user is logged in, get real addresses from database
    if (Auth::check()) {
        $addresses = Address::getUserAddresses(Auth::id());
        
        // Convert to the same format as dummy addresses
        return $addresses->map(function($address) {
            return [
                'id' => $address->id,
                'label' => $address->label,
                'full_name' => $address->full_name,
                'phone_number' => $address->phone,
                'email' => Auth::user()->email, // Keep email from user account
                'street_address' => $address->address_line_1 . ($address->address_line_2 ? ', ' . $address->address_line_2 : ''),
                'city' => $address->city,
                'postal_code' => $address->postal_code,
                'is_default' => $address->is_default
            ];
        })->toArray();
    }
    
    // Fallback to dummy data for guests (keep original dummy data)
    return [
        [
            'id' => 1,
            'label' => 'Home',
            'full_name' => 'Darrell Ocampo',
            'phone_number' => '+63 917 123 4567',
            'email' => '2100484@ub.edu.ph',
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
            'email' => '2100484@ub.edu.ph',
            'street_address' => '456 Business Ave, Barangay Poblacion',
            'city' => 'Bauan',
            'postal_code' => '4201',
            'is_default' => false
        ]
    ];
}

/**
 * Handle Buy Now functionality - create quick checkout session
 */
public function buyNow(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'nullable|integer|min:1|max:99'
    ]);
    
    try {
        // Get product with relationships
        $product = Product::with(['vendor', 'category', 'defaultVariant'])->findOrFail($request->product_id);
        
        // Check if product is active and in stock
        if ($product->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ]);
        }
        
        // Check stock availability
        $quantity = $request->quantity ?? 1;
        if (!$product->isInStock() || $product->getTotalStock() < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock available'
            ]);
        }
        
        // Create quick checkout item
        $quickCheckoutItem = [
            'product_id' => $product->id,
            'variant_id' => null, // Default variant for now
            'product_name' => $product->name,
            'vendor_id' => $product->vendor_id,
            'vendor_name' => $product->vendor->business_name,
            'price' => $product->getDisplayPrice(),
            'original_price' => $product->getOriginalPrice(),
            'quantity' => $quantity,
            'max_quantity' => $product->getTotalStock(),
            'image' => $product->images[0] ?? null
        ];
        
        // Create cart key
        $cartKey = $product->id . '_default';
        
        // Store in quick checkout session (separate from main cart)
        $quickCart = [$cartKey => $quickCheckoutItem];
        session()->put('quick_checkout_cart', $quickCart);
        session()->put('is_quick_checkout', true);
        
        return response()->json([
            'success' => true,
            'message' => 'Redirecting to checkout...',
            'redirect_url' => route('test.checkout.index')
        ]);
        
    } catch (\Exception $e) {
        Log::error('Buy Now failed: ' . $e->getMessage(), [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to process request. Please try again.'
        ]);
    }
}

/**
 * Checkout specific vendor items from cart
 */
public function checkoutVendor(Request $request)
{
    $request->validate([
        'vendor_id' => 'required|integer',
        'cart_keys' => 'nullable|array' // Optional: specific items to checkout
    ]);
    
    // Get cart from session
    $cart = session()->get('cart', []);
    
    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Cart is empty'
        ]);
    }
    
    // Filter cart items by vendor
    $vendorCart = [];
    $vendorId = $request->vendor_id;
    
    // If specific cart keys provided, use only those
    if ($request->cart_keys && !empty($request->cart_keys)) {
        foreach ($request->cart_keys as $cartKey) {
            if (isset($cart[$cartKey]) && $cart[$cartKey]['vendor_id'] == $vendorId) {
                $vendorCart[$cartKey] = $cart[$cartKey];
            }
        }
    } else {
        // Get all items from this vendor
        foreach ($cart as $cartKey => $item) {
            if ($item['vendor_id'] == $vendorId) {
                $vendorCart[$cartKey] = $item;
            }
        }
    }
    
    if (empty($vendorCart)) {
        return response()->json([
            'success' => false,
            'message' => 'No items found for this vendor'
        ]);
    }
    
    // Store vendor-specific cart in checkout session
    session()->put('checkout_cart', $vendorCart);
    session()->put('is_vendor_checkout', true);
    session()->put('checkout_vendor_id', $vendorId);
    
    return response()->json([
        'success' => true,
        'message' => 'Redirecting to checkout...',
        'redirect_url' => route('test.checkout.index')
    ]);
}

/**
 * Checkout all selected items from cart
 */
public function checkoutAll(Request $request)
{
    $request->validate([
        'cart_keys' => 'nullable|array' // Optional: specific items to checkout
    ]);
    
    // Get cart from session
    $cart = session()->get('cart', []);
    
    if (empty($cart)) {
        return response()->json([
            'success' => false,
            'message' => 'Cart is empty'
        ]);
    }
    
    $checkoutCart = [];
    
    // If specific cart keys provided, use only those
    if ($request->cart_keys && !empty($request->cart_keys)) {
        foreach ($request->cart_keys as $cartKey) {
            if (isset($cart[$cartKey])) {
                $checkoutCart[$cartKey] = $cart[$cartKey];
            }
        }
    } else {
        // Use entire cart
        $checkoutCart = $cart;
    }
    
    if (empty($checkoutCart)) {
        return response()->json([
            'success' => false,
            'message' => 'No items selected for checkout'
        ]);
    }
    
    // Store checkout cart in session
    session()->put('checkout_cart', $checkoutCart);
    session()->put('is_vendor_checkout', false);
    session()->forget('checkout_vendor_id');
    
    return response()->json([
        'success' => true,
        'message' => 'Redirecting to checkout...',
        'redirect_url' => route('test.checkout.index')
    ]);
}
}