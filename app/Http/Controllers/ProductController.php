<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\Cache;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category') && $request->category !== 'semua') {
            $query->where('category', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }

        match ($request->sort ?? 'latest') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating'     => $query->orderBy('rating', 'desc'),
            'popular'    => $query->orderBy('total_sold', 'desc'),
            default      => $query->latest(),
        };

        $products = $query->get();

        $banners = Cache::remember('active_banners', now()->addMinutes(30), function () {
            return Banner::active()
                ->limit(5)
                ->get();
        });

        $categories = $this->getCategories();

        $maxPrice = Product::max('price');

        $hotItems = Product::where('total_sold', '>', 0)
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        if ($request->has('partial')) {
            return view('products._grid', compact('products', 'hotItems'));
        }

        return view('products.index', compact(
            'products',
            'categories',
            'maxPrice',
            'hotItems',
            'banners'
        ));
    }

    public function promo()
    {
        $products = Product::where('is_promo', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->latest()
            ->get();

        $banners = Cache::remember('active_banners', now()->addMinutes(30), function () {
            return Banner::active()
                ->limit(5)
                ->get();
        });

        $categories = $this->getCategories();

        return view('products.promo', compact(
            'products',
            'categories',
            'banners'
        ));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $order_id = request('order_id');

        return view('products.show', compact('product', 'order_id'));
    }

    private function getCategories()
    {
        return Product::select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->category,
                    'img'  => strtolower(str_replace(' ', '-', $item->category)) . '.png',
                ];
            });
    }
}