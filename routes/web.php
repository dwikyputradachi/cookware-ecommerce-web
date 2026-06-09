<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\AdminSecurityController;

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
// Auth Admin (public)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::get('/admin/logout', fn() => redirect()->route('admin.login'));

// Protected Admin Routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminController::class, 'orders'])->name('index');
        Route::get('/{order}', [AdminController::class, 'showOrder'])->name('show');
        Route::post('/{order}/approve', [AdminController::class, 'approveOrder'])->name('approve');
        Route::post('/{order}/reject', [AdminController::class, 'rejectOrder'])->name('reject');
        Route::post('/{order}/archive', [AdminController::class, 'archiveOrder'])->name('archive');
        Route::post('/{order}/restore', [AdminController::class, 'restoreOrder'])->name('restore');
    });

    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminController::class, 'indexProducts'])->name('index');
        Route::get('/create', [AdminController::class, 'createProduct'])->name('create');
        Route::post('/', [AdminController::class, 'storeProduct'])->name('store');
        Route::get('/{product}/edit', [AdminController::class, 'editProduct'])->name('edit');
        Route::put('/{product}', [AdminController::class, 'updateProduct'])->name('update');
        Route::delete('/{product}', [AdminController::class, 'destroyProduct'])->name('destroy');
    });
    Route::prefix('payments')->name('payments.')->group(function () {
    Route::get('/', [PaymentSettingController::class, 'index'])->name('index');
    Route::get('/{payment}/edit', [PaymentSettingController::class, 'edit'])->name('edit');
    Route::put('/{payment}', [PaymentSettingController::class, 'update'])->name('update');
    Route::patch('/{payment}/toggle', [PaymentSettingController::class, 'toggleActive'])->name('toggle');
    });

    // Banner di luar prefix products
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::patch('banners/{banner}/toggle', [BannerController::class, 'toggleActive'])->name('banners.toggle');
    Route::get('/pages', [AdminController::class, 'indexPages'])->name('pages.index');
    Route::get('/pages/{page}/edit', [AdminController::class, 'editPage'])->name('pages.edit');
    Route::put('/pages/{page}', [AdminController::class, 'updatePage'])->name('pages.update');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/security', [AdminSecurityController::class, 'index'])->name('security.index');
    Route::post('/security/setup-authenticator', [AdminSecurityController::class, 'setupAuthenticator'])->name('security.setup-authenticator');
    Route::post('/security/reset-authenticator', [AdminSecurityController::class, 'resetAuthenticator'])->name('security.reset-authenticator');
    Route::post('/security/update-account', [AdminSecurityController::class, 'updateAccount'])->name('security.update-account');
});
// Route untuk halaman informasi dinamis
Route::get('/about-us', [PageController::class, 'show'])->name('about.us')->defaults('slug', 'about-us');

Route::get('/garansi', [PageController::class, 'show'])->name('garansi')->defaults('slug', 'garansi');

Route::get('/bantuan', [PageController::class, 'show'])->name('bantuan')->defaults('slug', 'bantuan');

Route::get('/penipuan', [PageController::class, 'show'])->name('penipuan')->defaults('slug', 'penipuan');

Route::get('/panduan', [PageController::class, 'show'])->name('panduan')->defaults('slug', 'panduan');

Route::get('/return', [PageController::class, 'show'])->name('return')->defaults('slug', 'return');