<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
    'title', 
    'image', 
    'link', 
    'is_active', 
    'sort_order' // Pastikan ini sort_order, bukan order
];
}
