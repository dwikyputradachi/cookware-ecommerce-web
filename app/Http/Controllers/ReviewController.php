<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id'    => 'required|exists:products,id',
            'order_id'      => 'required|exists:orders,id',
            'customer_name' => 'required|string|max:100',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|min:5',
        ]);

        $order = Order::with('items')->findOrFail($validated['order_id']);

        if ($order->status !== 'completed') {
            return back()->with('error', 'Ulasan hanya bisa diberikan setelah pesanan disetujui.');
        }

        $productInOrder = $order->items()
            ->where('product_id', $validated['product_id'])
            ->exists();

        if (!$productInOrder) {
            return back()->with('error', 'Produk ini tidak ditemukan di pesanan tersebut.');
        }

        $alreadyReviewed = Review::where('order_id', $validated['order_id'])
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk produk ini.');
        }

        DB::transaction(function () use ($validated) {
            Review::create($validated);

            $avgRating = Review::where('product_id', $validated['product_id'])
                ->avg('rating');

            Product::where('id', $validated['product_id'])
                ->update([
                    'rating' => $avgRating ? round($avgRating, 1) : 0,
                ]);
        });

        return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih.');
    }
}