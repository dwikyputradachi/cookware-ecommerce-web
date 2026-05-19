<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Paksa HTTPS di lingkungan produksi (Railway)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // 2. View Composer untuk Kategori Produk dan Pengaturan Situs
        View::composer('*', function ($view) {
            $categories = Product::distinct()
                ->pluck('category')
                ->filter()
                ->map(fn($name) => [
                    'name' => $name,
                    'img'  => strtolower(str_replace(' ', '-', $name)) . '.png'
                ])
                ->prepend(['name' => 'Semua', 'img' => 'all-products.png']);

            $siteSettings = Setting::pluck('value', 'key')->all();

            $view->with(compact('categories', 'siteSettings'));
        });
    }
}