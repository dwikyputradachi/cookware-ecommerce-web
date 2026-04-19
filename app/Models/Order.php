<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'payment_method',
        'total_price',
        'status',
        'payment_proof'
        
    ];

    // Relasi: 1 Order punya banyak Items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}