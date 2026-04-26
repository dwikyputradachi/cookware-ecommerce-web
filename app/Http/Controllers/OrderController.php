<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    private function getCategories()
    {
        $categoryNames = Product::distinct()->pluck('category')->filter();
        $mapped = $categoryNames->map(fn($name) => [
            'name' => $name,
            'img'  => strtolower(str_replace(' ', '-', $name)) . '.png'
        ]);
        return $mapped->prepend(['name' => 'Semua', 'img' => 'all-products.png']);
    }

    public function index()
    {
        $categories = $this->getCategories();
        return view('orders.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:8',
        ]);

        $categories = $this->getCategories();
        $orders = Order::with('items.product')
                    ->where('customer_phone', $request->phone)
                    ->latest()
                    ->get();

        return view('orders.index', compact('categories', 'orders'));
    }
}