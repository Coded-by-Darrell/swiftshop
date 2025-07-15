<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'spec_name',
        'spec_value',
        'display_order'
    ];

    protected $casts = [
        'display_order' => 'integer'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scope for ordered display
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}