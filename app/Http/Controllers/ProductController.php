<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::where('is_active', true)
                         ->orderBy('sort_order')
                         ->get();

        $categories = $this->getCategories();
        $query = Product::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Logic Baru: Filter dijalankan HANYA JIKA kategori bukan 'semua'
        if ($request->filled('category') && $request->category !== 'semua') {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->get();

        return view('products.index', compact('products', 'categories', 'banners'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $categories = $this->getCategories();
        return view('products.show', compact('product', 'categories'));
    }

    private function getCategories()
    {
        // Ambil kategori unik dari database
        $categoryNames = Product::distinct()->pluck('category')->filter();
        
        $mapped = $categoryNames->map(function($name) {
            return [
                'name' => $name,
                'img' => strtolower(str_replace(' ', '-', $name)) . '.png' 
            ];
        });

        // TAMBAHKAN INI: Masukkan kategori "Semua" di urutan paling atas
        return $mapped->prepend([
            'name' => 'Semua',
            'img' => 'all-products.png' // Pastikan file gambar ini ada atau abaikan jika tidak pakai icon
        ]);
    }
}