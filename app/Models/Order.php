<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['customer_id',
        'coupon_id',
        'subtotal',          
        'shipping_fee',      
        'platform_fee',      
        'discount_amount',   
        'total_amount',
        'shipping_address',
        'latitude',
        'longitude',
        'status',];

public function customer() {
    return $this->belongsTo(User::class, 'customer_id');
}

public function items() {
    return $this->hasMany(OrderItem::class);
}

public function payment() {
    return $this->hasOne(Payment::class);
}

public function coupon()
{
    return $this->belongsTo(Coupon::class);
}
}
