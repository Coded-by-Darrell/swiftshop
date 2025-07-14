<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'required',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'required' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Relationship: Attribute has many values
    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class)->where('is_active', true)->orderBy('sort_order');
    }

    // Relationship: All values including inactive
    public function allValues()
    {
        return $this->hasMany(ProductAttributeValue::class)->orderBy('sort_order');
    }

    // Scope: Active attributes only
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // Scope: Required attributes only
    public function scopeRequired($query)
    {
        return $query->where('required', true);
    }
}