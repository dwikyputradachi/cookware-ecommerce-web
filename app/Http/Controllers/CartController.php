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

    /**
     * Tambah Produk ke Keranjang
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // 1. Cek Stok (Mencegah beli barang melebihi stok yang ada)
        $currentQtyInCart = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        if ($product->stock <= $currentQtyInCart) {
            return back()->with('error', 'Maaf, stok tidak mencukupi!');
        }

        if (isset($cart[$id])) {
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

        if ($request->input('redirect') === 'cart') {
            return redirect('/cart');
        }   
        
        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $data = json_decode($request->getContent(), true) ?? $request->all();
            $action = $data['action'] ?? null;

            if ($action == 'plus') {
                // Opsional: Tambahkan cek stok juga di sini agar tidak tembus via tombol +
                $product = Product::find($id);
                if ($product && $product->stock > $cart[$id]['quantity']) {
                    $cart[$id]['quantity']++;
                } else {
                    return response()->json(['success' => false, 'error' => 'Stok habis']);
                }
            }

            if ($action == 'minus') {
                $cart[$id]['quantity']--;
                if ($cart[$id]['quantity'] <= 0) {
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
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    /**
     * Proses Checkout & Potong Stok
     */
    public function checkout(Request $request)
    {
        try {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Keranjang kosong atau session telah habis.'
                ], 400);
            }

            // Susun string produk untuk WhatsApp
            $itemDetails = [];
            foreach ($cart as $item) {
                $itemDetails[] = $item['name'] . " (" . $item['quantity'] . "x)";
            }
            $itemsString = implode(", ", $itemDetails);

            // Hitung total harga di server
            $totalPriceServer = collect($cart)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            // Handle Bukti Pembayaran
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $path = $request->file('payment_proof')->store('proofs', 'public'); 
                $proofPath = 'storage/' . $path;
            }

            // 1. Simpan Data Order
            $order = Order::create([
                'customer_name'    => $request->name,
                'customer_phone'   => $request->phone,
                'customer_address' => $request->address,
                'payment_method'   => $request->payment_method,
                'total_price'      => $totalPriceServer, 
                'status'           => ($request->payment_method == 'cod') ? 'pending' : 'waiting_verification',
                'payment_proof'    => $proofPath,
            ]);

            // 2. Simpan Detail Item & Potong Stok
            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            
                $product = Product::find($id); // Gunakan Product::find
                if ($product) {
                    if ($product->stock >= $item['quantity']) {
                        // FIX TYPO: tadi kamu tulis $itemDetails['quantity'], harusnya $item['quantity']
                        $product->decrement('stock', $item['quantity']);
                    } else {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                    }
                }
            }

            // 3. Bersihkan Keranjang
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