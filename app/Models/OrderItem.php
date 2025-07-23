<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'variant_id',
        'product_name',
        'vendor_name',
        'unit_price',
        'quantity',
        'total_price',
        'product_snapshot',
        'status',
        'vendor_confirmed_at',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'product_snapshot' => 'array',
        'vendor_confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Scopes
     */
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Accessors
     */
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total_price, 2);
    }

    public function getFormattedUnitPriceAttribute()
    {
        return '₱' . number_format($this->unit_price, 2);
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'ready_for_pickup' => 'bg-primary',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Helper Methods
     */
    public function canBeProcessed()
    {
        return $this->status === 'pending';
    }

    public function canBeShipped()
    {
        return in_array($this->status, ['processing', 'ready_for_pickup']);
    }

    public function canBeDelivered()
    {
        return $this->status === 'shipped';
    }
}