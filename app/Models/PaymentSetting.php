<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'payment_key', 'label', 'account_number',
        'account_name', 'qr_image', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];
}