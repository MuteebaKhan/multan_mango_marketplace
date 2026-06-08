<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity_kg'];

    // Product ke sath connection (taake cart page par product ka naam aur image nikal sakein)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}