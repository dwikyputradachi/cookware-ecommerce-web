<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // Pastikan ini juga ada
class CartController extends Controller
{
    /**
     * Menampilkan Halaman Keranjang
     */
    public function index()
    {
        // PERBAIKAN: Ambil data dari session dan kirim ke view
        $cart = session()->get('cart', []);
        
        return view('cart.index', compact('cart'));
    }

    /**
     * Menambah Produk ke Keranjang
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1,
                "image" => $product->image,
                // PENTING: Tambahkan ini agar fitur filter COD di Blade jalan
                "is_cod_available" => $product->is_cod_available 
            ];
        }

        session()->put('cart', $cart);

        // Logic Redirect: Ke keranjang atau tetap di halaman produk
        if ($request->input('redirect') === 'cart') {
            return redirect('/cart');
        }   
        
        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            // Handle JSON request dari fetch API
            $data = json_decode($request->getContent(), true) ?? $request->all();
            $action = $data['action'] ?? null;

            if($action == 'plus') {
                $cart[$id]['quantity']++;
            }

            if($action == 'minus') {
                $cart[$id]['quantity']--;

                // Jika jumlah 0, hapus dari keranjang
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
   public function checkout(Request $request)
    {
        try {
            $cart = session()->get('cart', []);
            
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                
                // Simpan ke folder storage/app/public/proofs
                // Fungsi store() otomatis buat nama random agar tidak bentrok
                $path = $file->store('proofs', 'public'); 
                $proofPath = 'storage/' . $path;
            }

            $order = Order::create([
                'customer_name'    => $request->name,
                'customer_phone'   => $request->phone,
                'customer_address' => $request->address,
                'payment_method'   => $request->payment_method,
                'total_price'      => $request->total_price, // Pastikan ini dikirim/dihitung
                'status'           => ($request->payment_method == 'cod') ? 'pending' : 'waiting_verification',
                'payment_proof'    => $proofPath, // PIN PENTING: Pastikan variabel ini ada di sini
            ]);

            // 2. Simpan Items
            foreach($cart as $id => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
            }

            session()->forget('cart');
            return response()->json(['success' => true, 'order_id' => $order->id]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}