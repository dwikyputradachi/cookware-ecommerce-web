<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $categories = Product::distinct()
                ->pluck('category')
                ->filter()
                ->map(fn($name) => [
                    'name' => $name,
                    'img'  => strtolower(str_replace(' ', '-', $name)) . '.png'
                ])
                ->prepend(['name' => 'Semua', 'img' => 'all-products.png']);

            $view->with('categories', $categories);
        });
    }
}