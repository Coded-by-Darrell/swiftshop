<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'category_id', 
        'name',
        'description',
        'price',
        'stock_quantity',
        'images',
        'status'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2'
    ];

    // Relationship: Product belongs to Vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relationship: Product belongs to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // NEW RELATIONSHIPS FOR VARIANTS SYSTEM
    
    // Relationship: Product has many variants
    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    // Relationship: All variants including inactive
    public function allVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Get default variant
    public function defaultVariant()
    {
        return $this->hasOne(ProductVariant::class)->where('is_default', true)->where('is_active', true);
    }

    // Get the main display price (from default variant or base price)
    public function getDisplayPrice()
    {
        $defaultVariant = $this->defaultVariant;
        if ($defaultVariant) {
            return $defaultVariant->getCurrentPrice();
        }
        return $this->price;
    }

    // Get the original price (for discount calculations)
    public function getOriginalPrice()
    {
        $defaultVariant = $this->defaultVariant;
        if ($defaultVariant) {
            return $defaultVariant->original_price;
        }
        return $this->price;
    }

    // Check if product has variants
    public function hasVariants()
    {
        return $this->variants()->count() > 0;
    }

    // Check if any variant is on sale
    public function hasActiveDiscount()
    {
        if ($this->hasVariants()) {
            return $this->variants()->onSale()->count() > 0;
        }
        return false;
    }

    // Get discount percentage (from default variant)
    public function getDiscountPercentage()
    {
        $defaultVariant = $this->defaultVariant;
        if ($defaultVariant) {
            return $defaultVariant->getDiscountPercentage();
        }
        return 0;
    }

    // Check if product is in stock
    public function isInStock()
    {
        if ($this->hasVariants()) {
            return $this->variants()->inStock()->count() > 0;
        }
        return $this->stock_quantity > 0;
    }

    // Get total stock from all variants
    public function getTotalStock()
    {
        if ($this->hasVariants()) {
            return $this->variants()->sum('stock_quantity');
        }
        return $this->stock_quantity;
    }

    // Scope: Active products
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope: In stock products
    public function scopeInStock($query)
    {
        return $query->where(function($q) {
            $q->whereHas('variants', function($variant) {
                $variant->inStock();
            })->orWhere(function($base) {
                $base->whereDoesntHave('variants')->where('stock_quantity', '>', 0);
            });
        });
    }

    // Scope: On sale products
    public function scopeOnSale($query)
    {
        return $query->whereHas('variants', function($variant) {
            $variant->onSale();
        });
    }

        // Add this method to your existing Product model
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    // Helper method to get average rating
    public function averageRating()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    // Helper method to get total reviews count
    public function reviewsCount()
    {
        return $this->approvedReviews()->count();
    }
}