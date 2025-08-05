<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Add these methods to your existing User model
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function vendorReviews()
    {
        return $this->hasMany(VendorReview::class);
    }

    public function addresses()
{
    return $this->hasMany(Address::class)->orderBy('is_default', 'desc')->orderBy('created_at', 'desc');
}

public function getDefaultAddress()
{
    return $this->addresses()->where('is_default', true)->first();
}

/**
 * Get the user's wishlist items
 */
public function wishlistItems()
{
    return $this->hasMany(WishlistItem::class);
}

/**
 * Get the user's wishlist products
 */
public function wishlistProducts()
{
    return $this->belongsToMany(Product::class, 'wishlist_items')->withTimestamps();
}

/**
 * Check if user has product in wishlist
 */
public function hasInWishlist($productId)
{
    return $this->wishlistItems()->where('product_id', $productId)->exists();
}
}
