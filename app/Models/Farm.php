<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $fillable = ['user_id', 'farm_name', 'location', 'description', 'is_verified', 'profile_image'];
    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function products() {
        return $this->hasMany(Product::class);
    }
}
