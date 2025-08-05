<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'label',
        'full_name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'postal_code',
        'country',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessors - Adapted to your field names
     */
    public function getFormattedAddressAttribute()
    {
        $address = $this->address_line_1;
        if ($this->address_line_2) {
            $address .= ', ' . $this->address_line_2;
        }
        return "{$address}, {$this->city}, {$this->postal_code}";
    }

    public function getFullAddressAttribute()
    {
        return [
            'full_name' => $this->full_name,
            'phone_number' => $this->phone,
            'street_address' => $this->address_line_1 . ($this->address_line_2 ? ', ' . $this->address_line_2 : ''),
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'country' => $this->country
        ];
    }

    /**
     * Static Methods
     */
    public static function getUserAddresses($userId)
    {
        return static::where('user_id', $userId)
                    ->orderBy('is_default', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }
}