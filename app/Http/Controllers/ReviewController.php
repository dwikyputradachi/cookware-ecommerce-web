<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'order_id'      => 'required|exists:orders,id',
            'customer_name' => 'required|string|max:100',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|min:5',
        ]);

        // Cek apakah order ini sudah pernah review produk ini
        $alreadyReviewed = Review::where('order_id', $request->order_id)
                                  ->where('product_id', $request->product_id)
                                  ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk produk ini.');
        }

        // Simpan review
        Review::create([
            'product_id'    => $request->product_id,
            'order_id'      => $request->order_id,
            'customer_name' => $request->customer_name,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
        ]);

        // Update rata-rata rating produk otomatis
        $avgRating = Review::where('product_id', $request->product_id)->avg('rating');
        Product::where('id', $request->product_id)->update(['rating' => round($avgRating, 1)]);

        return back()->with('success', 'Ulasan berhasil dikirim! Terima kasih.');
    }
}