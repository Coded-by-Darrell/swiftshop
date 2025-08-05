<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id'
    ];

    /**
     * Get the user that owns the wishlist item
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the wishlist item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if a product is in user's wishlist
     */
    public static function isInWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)
                  ->where('product_id', $productId)
                  ->exists();
    }

    /**
     * Add product to wishlist
     */
    public static function addToWishlist($userId, $productId)
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    /**
     * Remove product from wishlist
     */
    public static function removeFromWishlist($userId, $productId)
    {
        return self::where('user_id', $userId)
                  ->where('product_id', $productId)
                  ->delete();
    }

    /**
     * Get user's wishlist items with product details
     */
    public static function getUserWishlist($userId)
    {
        return self::where('user_id', $userId)
                  ->with(['product.vendor', 'product.category'])
                  ->orderBy('created_at', 'desc')
                  ->get();
    }

    /**
     * Get wishlist count for user
     */
    public static function getWishlistCount($userId)
    {
        return self::where('user_id', $userId)->count();
    }
}