<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Paksa HTTPS di lingkungan produksi Railway
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $categories = collect();
            $siteSettings = [];

            if (Schema::hasTable('products')) {
                $categories = Product::distinct()
                    ->pluck('category')
                    ->filter()
                    ->map(fn($name) => [
                        'name' => $name,
                        'img'  => strtolower(str_replace(' ', '-', $name)) . '.png'
                    ])
                    ->prepend(['name' => 'Semua', 'img' => 'all-products.png']);
            }

            if (Schema::hasTable('settings')) {
                $siteSettings = Setting::pluck('value', 'key')->all();
            }

            $view->with(compact('categories', 'siteSettings'));
        });
    }
}