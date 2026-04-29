<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'shipping_address',
        'payment_method',
        'total_price',
        'status',
        'payment_proof'
    ];

    // Relasi: Order milik User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 Order punya banyak Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}