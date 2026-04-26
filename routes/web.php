<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/promo', [ProductController::class, 'promo'])->name('products.promo');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
Route::post('/pesanan', [OrderController::class, 'search'])->name('orders.search');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Route Cart
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add/{id}', [CartController::class, 'add']);
Route::post('/cart/update/{id}', [CartController::class, 'update']);
Route::post('/cart/remove/{id}', [CartController::class, 'remove']);
Route::post('/checkout', [CartController::class, 'checkout']);
Route::get('/clear-cart', function() {
    session()->forget('cart');
    return redirect('/cart');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Products CRUD
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminController::class, 'indexProducts'])->name('index');
        Route::get('/create', [AdminController::class, 'createProduct'])->name('create');
        Route::post('/', [AdminController::class, 'storeProduct'])->name('store');
        Route::get('/{product}/edit', [AdminController::class, 'editProduct'])->name('edit');
        Route::put('/{product}', [AdminController::class, 'updateProduct'])->name('update');
        Route::delete('/{product}', [AdminController::class, 'destroyProduct'])->name('destroy');
    });
});

// ROUTE BAHASA (Wajib di luar grup admin)
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
});
// Route untuk halaman Tentang Kami
Route::get('/about-us', function () {
    return view('aboutus'); 
})->name('about.us');

Route::get('/garansi', function () {
    return view('garansi');
})->name('garansi');

Route::get('/bantuan', function () {
    return view('bantuan');
})->name('bantuan');

Route::get('/penipuan', function () {
    return view('penipuan');
})->name('penipuan');

Route::get('/panduan', function () {
    return view('panduan');
})->name('panduan');

Route::get('/return', function () {
    return view('returnbarang');
})->name('return');