<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_order_amount', 'expiry_date', 'is_active'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
