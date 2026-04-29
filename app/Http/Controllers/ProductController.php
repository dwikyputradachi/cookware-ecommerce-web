<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
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
         // Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Rating
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Stok tersedia
        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // Urutkan
        match($request->sort ?? 'latest') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating'     => $query->orderBy('rating', 'desc'),
            'popular'    => $query->orderBy('total_sold', 'desc'),
            default      => $query->latest(),
        };


        $products    = $query->get();
        $maxPrice    = Product::max('price');
        $hotItems    = Product::where('total_sold', '>', 0)
                        ->orderBy('total_sold', 'desc')
                        ->take(5)
                        ->get();
        if ($request->has('partial')) {
        return view('products._grid', compact('products', 'hotItems'));
    }

        return view('products.index', compact('products', 'categories', 'maxPrice', 'hotItems'));
    }
    public function promo()
    {
        $categories = $this->getCategories();
        $products = Product::where('is_promo', true)->latest()->get();
        return view('products.promo', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $categories = $this->getCategories();
        $order_id  = request('order_id'); // Ambil dari query string
        return view('products.show', compact('product', 'categories', 'order_id'));
        
    }

    private function getCategories()
    {
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
