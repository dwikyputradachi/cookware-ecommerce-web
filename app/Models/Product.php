<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_cod_available'
    ];
    public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
}