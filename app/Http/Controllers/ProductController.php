<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;

class ProductController extends Controller
{
    /**
     * Menampilkan Katalog Produk
     */
    public function index(Request $request)
    {
        // 1. Ambil Banner yang aktif
        $banners = Banner::where('is_active', true)
                         ->orderBy('sort_order')
                         ->get();

        // 2. Ambil Kategori unik untuk filter tab
        $categoryNames = Product::distinct()->pluck('category')->filter();

        $categories = $categoryNames->map(function($name) {
            return [
                'name' => $name,
                // Pastikan file ini ada di public/images/categories/nama-kategori.png
                'img' => strtolower(str_replace(' ', '-', $name)) . '.png' 
            ];
        });

        // 3. Bangun Query Produk
        $query = Product::query();

        // Filter Berdasarkan Search (Cek Nama & Deskripsi)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter Berdasarkan Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Eksekusi query
        $products = $query->latest()->get();

        return view('products.index', compact('products', 'categories', 'banners'));
    }
    
    /**
     * Menampilkan Detail Produk
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
    
}