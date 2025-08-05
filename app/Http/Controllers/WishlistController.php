<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist
     */
    public function index()
    {
        $user = Auth::user();
        $wishlistItems = WishlistItem::getUserWishlist($user->id);
        
        // Transform wishlist items to match your product card format
        $products = $wishlistItems->map(function ($item) {
            $product = $item->product;
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->getDisplayPrice(),
                'old_price' => $product->hasActiveDiscount() ? $product->getOriginalPrice() : null,
                'rating' => $product->averageRating(),
                'reviewsCount' => $product->reviewsCount(),
                'store' => $product->vendor->business_name ?? 'Unknown Store',
                'badge' => $product->hasActiveDiscount() ? $product->getDiscountPercentage() . '% OFF' : null,
                'wishlist_id' => $item->id, // For removal purposes
            ];
        });
        
        return view('account.wishlist', compact('products'));
    }

    /**
     * Add product to wishlist (AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        // Check if already in wishlist
        if (WishlistItem::isInWishlist($user->id, $productId)) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in your wishlist!'
            ]);
        }

        // Add to wishlist
        WishlistItem::addToWishlist($user->id, $productId);

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist!',
            'wishlist_count' => WishlistItem::getWishlistCount($user->id)
        ]);
    }

    /**
     * Remove product from wishlist (AJAX)
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        // Remove from wishlist
        $removed = WishlistItem::removeFromWishlist($user->id, $productId);

        if ($removed) {
            return response()->json([
                'success' => true,
                'message' => 'Removed from wishlist!',
                'wishlist_count' => WishlistItem::getWishlistCount($user->id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in wishlist!'
        ]);
    }

    /**
     * Toggle wishlist status (AJAX)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        if (WishlistItem::isInWishlist($user->id, $productId)) {
            // Remove from wishlist
            WishlistItem::removeFromWishlist($user->id, $productId);
            
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from wishlist!',
                'wishlist_count' => WishlistItem::getWishlistCount($user->id)
            ]);
        } else {
            // Add to wishlist
            WishlistItem::addToWishlist($user->id, $productId);
            
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to wishlist!',
                'wishlist_count' => WishlistItem::getWishlistCount($user->id)
            ]);
        }
    }

    /**
     * Get wishlist count (AJAX)
     */
    public function getWishlistCount()
    {
        $user = Auth::user();
        
        return response()->json([
            'count' => WishlistItem::getWishlistCount($user->id)
        ]);
    }

    /**
     * Check if product is in wishlist (AJAX)
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $isInWishlist = WishlistItem::isInWishlist($user->id, $request->product_id);

        return response()->json([
            'in_wishlist' => $isInWishlist
        ]);
    }
}