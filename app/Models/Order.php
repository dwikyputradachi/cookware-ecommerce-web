<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'shipping_address',
        'payment_method',
        'total_price',
        'status',
        'payment_proof',
        'archived_at',
    ];

    protected $casts = [
        'total_price' => 'float',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'archived_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isWaitingVerification()
    {
        return in_array($this->status, ['waiting_verification', 'pending']);
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}