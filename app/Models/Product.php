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
}