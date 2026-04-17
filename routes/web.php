<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Route ini harus diakses via POST dari form
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index']);
Route::post('/cart/add/{id}', [CartController::class, 'add']);
Route::post('/cart/update/{id}', [CartController::class, 'update']);
Route::post('/cart/remove/{id}', [CartController::class, 'remove']);
Route::post('/checkout', [App\Http\Controllers\CartController::class, 'checkout']);