<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'original_price',
        'sale_price',
        'is_on_sale',
        'sale_start_date',
        'sale_end_date',
        'stock_quantity',
        'low_stock_threshold',
        'weight',
        'dimensions',
        'barcode',
        'is_active',
        'is_default'
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'dimensions' => 'array',
        'is_on_sale' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime'
    ];

    // Relationship: Variant belongs to product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship: Variant has many attribute values
    public function attributeValues()
    {
        return $this->belongsToMany(ProductAttributeValue::class, 'product_variant_attributes');
    }

    // Get current effective price
    public function getCurrentPrice()
    {
        if ($this->isCurrentlyOnSale()) {
            return $this->sale_price;
        }
        return $this->original_price;
    }

    // Check if currently on sale
    public function isCurrentlyOnSale()
    {
        if (!$this->is_on_sale || !$this->sale_price) {
            return false;
        }

        $now = Carbon::now();
        
        // Check sale date range
        if ($this->sale_start_date && $now->lt($this->sale_start_date)) {
            return false;
        }
        
        if ($this->sale_end_date && $now->gt($this->sale_end_date)) {
            return false;
        }

        return true;
    }

    // Get discount percentage
    public function getDiscountPercentage()
    {
        if (!$this->isCurrentlyOnSale()) {
            return 0;
        }

        return round((($this->original_price - $this->sale_price) / $this->original_price) * 100);
    }

    // Check if low stock
    public function isLowStock()
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    // Check if in stock
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    // Get variant display name (combination of attribute values)
    public function getDisplayName()
    {
        $values = $this->attributeValues->pluck('display_name')->toArray();
        return implode(' / ', $values);
    }

    // Scope: Active variants
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: In stock variants
    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    // Scope: On sale variants
    public function scopeOnSale($query)
    {
        return $query->where('is_on_sale', true)
                    ->where(function($q) {
                        $q->whereNull('sale_start_date')
                          ->orWhere('sale_start_date', '<=', Carbon::now());
                    })
                    ->where(function($q) {
                        $q->whereNull('sale_end_date')
                          ->orWhere('sale_end_date', '>=', Carbon::now());
                    });
    }
}