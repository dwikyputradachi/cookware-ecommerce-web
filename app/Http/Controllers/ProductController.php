<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $banners = collect([]);
    
        $categoryNames = \App\Models\Product::distinct()->pluck('category')->filter();
    
        $categories = $categoryNames->map(function($name) {
            return [
                'name' => $name,
                'img' => strtolower(str_replace(' ', '-', $name)) . '.png'
            ];
        });
    
        try {
            $query = Product::query();
    
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
            }
    
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }
    
            $products = $query->latest()->get();
    
        } catch (\Exception $e) {
            $products = [];
        }
    
        return view('products.index', compact('products', 'categories', 'banners'));
    }
}
