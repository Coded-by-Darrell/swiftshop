<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_email',
        'business_phone',
        'business_address',
        'status'
    ];

    // Relationship: Vendor has many Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Add this method to your existing Vendor model
    public function reviews()
    {
        return $this->hasMany(VendorReview::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(VendorReview::class)->where('is_approved', true);
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

    public function user()
{
    return $this->belongsTo(User::class);
}
}