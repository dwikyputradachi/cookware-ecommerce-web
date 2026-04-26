<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'category', 'description', 'price',
        'is_promo', 'discount_price', 'stock', 'total_sold',
        'rating', 'image', 'video_url', 'is_cod_available'
    ];

    // ← INI YANG PENTING
    protected $casts = [
        'is_promo'        => 'boolean',
        'discount_price'  => 'float',
        'price'           => 'float',
        'is_cod_available'=> 'boolean',
        'stock'           => 'integer',
        'total_sold'      => 'integer',
        'rating'          => 'float',
    ];
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // Tambahkan method ini di app/Models/Product.php
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}