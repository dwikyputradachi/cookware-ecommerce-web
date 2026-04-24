<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // Kode ini akan mengirim data $categories ke SEMUA halaman otomatis
        View::composer('*', function ($view) {
            $categories = [
                ['name' => 'Semua'],
                ['name' => 'Panci'],
                ['name' => 'Wajan'],
                ['name' => 'Spatula'],
                ['name' => 'Pisau'],
            ];
            $view->with('categories', $categories);
        });
    }
}