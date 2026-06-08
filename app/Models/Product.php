<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['farm_id', 'category_id', 'name', 'grade',  'price_per_kg', 'stock_quantity_kg', 'harvest_date', 'image_url', 'description', 'is_active'];

public function farm() {
    return $this->belongsTo(Farm::class);
}

public function category() {
    return $this->belongsTo(Category::class);
}

public function reviews() {
    return $this->hasMany(Review::class);
}
}
