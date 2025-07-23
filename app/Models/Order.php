<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',  // Keep this instead of customer_id
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_method',
        'order_notes',
        'subtotal',
        'shipping_fee',
        'tax_amount',
        'total_amount',
        'payment_method',
        'status',
        'estimated_delivery',
        'delivered_at'
    ];
    
    

    protected $casts = [
        'shipping_address' => 'array',
        'estimated_delivery' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id'); // Use user_id instead of customer_id
    }

    /**
     * Scopes
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCustomerEmail($query, $email)
    {
        return $query->where('customer_email', $email);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Accessors & Mutators
     */
    public function getFormattedTotalAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getEstimatedDeliveryDateAttribute()
    {
        if ($this->estimated_delivery) {
            return $this->estimated_delivery->format('M d, Y');
        }
        
        // Calculate based on shipping method if not set
        $days = match($this->shipping_method) {
            'same_day' => 0,
            'express' => 2,
            'standard' => 5,
            default => 5
        };
        
        return $this->created_at->addDays($days)->format('M d, Y');
    }

    /**
     * Helper Methods
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'shipped' => 'bg-primary',
            'delivered' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    public function getVendorGroups()
    {
        return $this->orderItems()
            ->with(['product', 'vendor'])
            ->get()
            ->groupBy('vendor_id');
    }

    /**
     * Static Methods
     */
    public static function generateOrderNumber()
    {
        do {
            $orderNumber = 'SW-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (static::where('order_number', $orderNumber)->exists());
        
        return $orderNumber;
    }

    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
    }
}