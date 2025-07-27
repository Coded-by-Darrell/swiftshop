<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Vendor;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        // Get cart from session
        $cart = session()->get('cart', []);
        
        // Group cart items by vendor for multi-vendor display
        $cartByVendor = $this->groupCartByVendor($cart);
        
        // Calculate totals
        $cartTotals = $this->calculateCartTotals($cart);
        
        // Get suggested products (you might also like)
        $suggestedProducts = $this->getSuggestedProducts();
        
        // User account data (following your pattern)
        $userAccount = Auth::user();
        
        return view('cart.index', compact(
            'cartByVendor',
            'cartTotals',
            'suggestedProducts',
            'userAccount'
        ));
    }
    
    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'variant_id' => 'nullable|exists:product_variants,id'
        ]);
        
        // Get product with relationships
        $product = Product::with(['vendor', 'category', 'defaultVariant'])->findOrFail($request->product_id);
        
        // Check if product is active and in stock
        if ($product->status !== 'active' || !$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available'
            ]);
        }
        
        // Get cart from session
        $cart = session()->get('cart', []);
        
        // Create cart item key
        $cartKey = $request->product_id . '_' . ($request->variant_id ?? 'default');
        
        // Prepare cart item data
        $cartItem = [
            'product_id' => $product->id,
            'variant_id' => $request->variant_id,
            'product_name' => $product->name,
            'vendor_id' => $product->vendor_id,
            'vendor_name' => $product->vendor->business_name,
            'price' => $product->getDisplayPrice(),
            'original_price' => $product->getOriginalPrice(),
            'quantity' => $request->quantity,
            'max_quantity' => $product->getTotalStock(),
            'image' => $product->images[0] ?? null
        ];
        
        // If item already exists in cart, update quantity
        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $request->quantity;
            
            // Check stock limit
            if ($newQuantity > $cartItem['max_quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity exceeds available stock'
                ]);
            }
            
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            // Add new item to cart
            $cart[$cartKey] = $cartItem;
        }
        
        // Save cart to session
        session()->put('cart', $cart);
        
        // Get updated cart count for response
        $cartCount = $this->getCartItemCount();
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartCount
        ]);
    }
    
    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string',
            'quantity' => 'required|integer|min:1|max:99'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->cart_key])) {
            // Check stock limit
            if ($request->quantity > $cart[$request->cart_key]['max_quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity exceeds available stock'
                ]);
            }
            
            $cart[$request->cart_key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            // Calculate new totals
            $cartTotals = $this->calculateCartTotals($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart_totals' => $cartTotals,
                'item_total' => $cart[$request->cart_key]['price'] * $request->quantity
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ]);
    }
    
    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'cart_key' => 'required|string'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->cart_key])) {
            unset($cart[$request->cart_key]);
            session()->put('cart', $cart);
            
            $cartCount = $this->getCartItemCount();
            $cartTotals = $this->calculateCartTotals($cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $cartCount,
                'cart_totals' => $cartTotals
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Item not found in cart'
        ]);
    }
    
    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully'
        ]);
    }
    
    /**
     * Get cart item count for navbar badge
     */
    public function getCartCount()
    {
        $cartCount = $this->getCartItemCount();
        
        return response()->json([
            'cart_count' => $cartCount
        ]);
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
     * Calculate cart totals
     */
    private function calculateCartTotals($cart)
    {
        $subtotal = 0;
        $totalItems = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }
        
        $shipping = 0; // Free shipping for now
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
     * Get cart item count
     */
    private function getCartItemCount()
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }
    
    /**
     * Get suggested products for "You might also like"
     */
    private function getSuggestedProducts()
    {
        return Product::with(['vendor', 'category', 'defaultVariant'])
            ->where('status', 'active')
            ->inStock()
            ->limit(4)
            ->get()
            ->map(function($product) {
                $discount = $product->getDiscountPercentage();
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->getDisplayPrice(),
                    'old_price' => $discount > 0 ? $product->getOriginalPrice() : null,
                    'store' => $product->vendor->business_name,
                    'badge' => $discount > 0 ? $discount . '% OFF' : null
                ];
            });
    }
}