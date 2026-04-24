<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Menampilkan Halaman Keranjang
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Ambil kategori agar navbar tidak error (Undefined variable $categories)
        $categories = $this->getCategories();

        return view('cart.index', compact('cart', 'categories'));
    }

    /**
     * Menambah Produk ke Keranjang
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

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

        return $request->input('redirect') === 'cart' 
            ? redirect('/cart') 
            : back()->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Update Quantity (Plus/Minus) via AJAX
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $data = json_decode($request->getContent(), true) ?? $request->all();
            $action = $data['action'] ?? null;

            if ($action == 'plus') {
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

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    /**
     * Menghapus Item dari Keranjang
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    /**
     * Proses Checkout dan Simpan ke Database
     */
    public function checkout(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return response()->json(['success' => false, 'error' => 'Keranjang kosong.'], 400);
            }

            $itemDetails = [];
            foreach ($cart as $item) {
                $itemDetails[] = $item['name'] . " (" . $item['quantity'] . "x)";
            }
            $itemsString = implode(", ", $itemDetails);

            $totalPriceServer = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('proofs', 'public'); 
            }

            // Simpan Data Order
            $order = Order::create([
                'customer_name'    => $request->name,
                'customer_phone'   => $request->phone,
                'customer_address' => $request->address,
                'payment_method'   => $request->payment_method,
                'total_price'      => $totalPriceServer, 
                'status'           => ($request->payment_method == 'cod') ? 'pending' : 'waiting_verification',
                'payment_proof'    => $proofPath,
            ]);

            // Simpan Detail Item & Kurangi Stok
            foreach ($cart as $id => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            
                $product = Product::lockForUpdate()->find($id); 
                if ($product) {
                    if ($product->stock >= $item['quantity']) {
                        $product->decrement('stock', $item['quantity']);
                    } else {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                    }
                }
            }

            // Bersihkan Keranjang
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
                    'payment_method' => strtoupper($order->payment_method),
                    'status_text' => ($order->payment_method == 'cod') ? 'Menunggu Pengiriman (COD)' : 'Menunggu Verifikasi Pembayaran'
                ]
            ]);
        });
    }

    /**
     * Helper untuk mengambil kategori (Navbar)
     */
    private function getCategories()
    {
        $categoryNames = Product::distinct()->pluck('category')->filter();
        
        $mapped = $categoryNames->map(function($name) {
            return [
                'name' => $name,
                'img' => strtolower(str_replace(' ', '-', $name)) . '.png' 
            ];
        });

        return $mapped->prepend([
            'name' => 'Semua',
            'img' => 'all-products.png'
        ]);
    }
}