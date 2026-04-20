<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category')) {
        $query->where('category', $request->category);
    }

    $products = $query->latest()->get();

        $banners = \App\Models\Banner::where('is_active', true)->orderBy('sort_order')->get();
        $categoryNames = \App\Models\Product::distinct()->pluck('category')->filter();

        $categories = $categoryNames->map(function($name) {
            return [
                'name' => $name,
                //Format file: misal "rice-cooker" ganti jadi ini ya "rice-cooker.png"
                'img' => strtolower(str_replace(' ', '-', $name)) . '.png' 
            ];
        });

        $query = \App\Models\Product::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $products = $query->latest()->get();

        return view('products.index', compact('products', 'categories', 'banners'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}