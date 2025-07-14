<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_attribute_id',
        'value',
        'display_value',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationship: Value belongs to attribute
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    // Relationship: Value used in many variants
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_attributes');
    }

    // Accessor: Get display name (use display_value if set, otherwise value)
    public function getDisplayNameAttribute()
    {
        return $this->display_value ?: $this->value;
    }

    // Scope: Active values only
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // Check if this is a color attribute
    public function isColor()
    {
        return $this->attribute && $this->attribute->name === 'Color';
    }
}