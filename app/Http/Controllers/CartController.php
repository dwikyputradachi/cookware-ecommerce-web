<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Menampilkan Halaman Keranjang
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

   public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // 1. Cek Stok (Mencegah beli barang gaib)
        $currentQtyInCart = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        if ($product->stock <= $currentQtyInCart) {
            return back()->with('error', 'Maaf, stok tidak mencukupi!');
        }

        // 2. Logika Tambah/Update Session
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "image" => $product->image,
                "is_cod_available" => (bool) $product->is_cod_available 
            ];
        }

        session()->put('cart', $cart);

        // 3. LOGIKA REDIRECT (Kuncinya di sini)
        // Kita cek input 'redirect' dari form
        if ($request->input('redirect') === 'cart') {
            return redirect('/cart'); // Paksa ke halaman keranjang
        }   
        
        // Kalau value-nya 'back' atau tidak ada, balik ke halaman sebelumnya
        return back()->with('success', 'Produk berhasil ditambahkan!');
    }
    /**
     * Update Quantity Keranjang
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $data = json_decode($request->getContent(), true) ?? $request->all();
            $action = $data['action'] ?? null;

            if($action == 'plus') {
                $cart[$id]['quantity']++;
            }

            if($action == 'minus') {
                $cart[$id]['quantity']--;
                if($cart[$id]['quantity'] <= 0){
                    unset($cart[$id]);
                }
            }

            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    /**
     * Hapus Item dari Keranjang
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    /**
     * Proses Checkout
     */
   public function checkout(Request $request)
    {
        try {
            $cart = session()->get('cart', []);

            // Jika error 400 muncul di sini, berarti session 'cart' kosong
            if (empty($cart)) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Keranjang kosong atau session telah habis. Silakan refresh halaman.'
                ], 400);
            }

            // Susun string produk
            $itemDetails = [];
            foreach ($cart as $item) {
                $itemDetails[] = $item['name'] . " (" . $item['quantity'] . "x)";
            }
            $itemsString = implode(", ", $itemDetails);

            // Hitung total harga di server (Keamanan agar tidak dimanipulasi)
            $totalPriceServer = collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('proofs', 'public'); 
                $proofPath = 'storage/' . $path;
            }

            $order = Order::create([
                'customer_name'    => $request->name,
                'customer_phone'   => $request->phone,
                'customer_address' => $request->address,
                'payment_method'   => $request->payment_method,
                'total_price'      => $totalPriceServer, 
                'status'           => ($request->payment_method == 'cod') ? 'pending' : 'waiting_verification',
                'payment_proof'    => $proofPath,
            ]);

            foreach($cart as $id => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            session()->forget('cart');

            return response()->json([
                'success' => true, 
                'order_id' => $order->id,
                'items_string' => $itemsString,
                'data_server' => [
                    'name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                    'address' => $order->customer_address,
                    'total_price' => number_format($order->total_price, 0, ',', '.'),
                    'payment_method' => $order->payment_method,
                    'status_text' => ($order->payment_method == 'cod') ? 'Menunggu Pengiriman (COD)' : 'Sudah Bayar (Verifikasi Admin)'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}