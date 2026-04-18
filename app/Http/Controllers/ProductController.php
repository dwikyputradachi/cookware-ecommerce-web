<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // Katalog produk
    public function index(Request $request)
{
    $query = Product::query();

    // Filter Pencarian
    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
    }

    // Filter Kategori
    if ($request->has('category') && $request->category != 'Semua') {
        $query->where('category', $request->category);
    }

    $products = $query->latest()->get();
    
    // Ambil daftar kategori unik untuk ditampilkan di tombol
    $categories = ['Semua', 'Wajan', 'Panci', 'Spatula', 'Set Alat Masak']; 

    return view('products.index', compact('products', 'categories'));
}

    // Detail produk
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}