<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;
use App\Models\PaymentSetting;
class CartController extends Controller
{
    private function uploadToCloudinary($file, $folder = 'general')
    {
        $cloudinaryUrl = config('services.cloudinary.url');

        if (!$cloudinaryUrl) {
            throw new \Exception('CLOUDINARY_URL belum terbaca di server.');
        }

        $parsed = parse_url($cloudinaryUrl);

        if (!isset($parsed['host'], $parsed['user'], $parsed['pass'])) {
            throw new \Exception('Format CLOUDINARY_URL tidak valid.');
        }

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $parsed['host'],
                'api_key'    => $parsed['user'],
                'api_secret' => $parsed['pass'],
            ],
        ]);

        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => $folder,
            'resource_type' => 'image',
        ]);

        return $result['secure_url'];
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $cart = array_filter($cart, fn($item) => isset($item['quantity']));
        
        session()->put('cart', $cart);
          $payments = PaymentSetting::where('is_active', true)->orderBy('id')->get();
        return view('cart.index', compact('payments', 'cart'));
    }
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        foreach ($cart as $key => $item) {
            if (!isset($item['quantity'])) {
                unset($cart[$key]);
            }
        }

        $currentQtyInCart = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;

        if ($product->stock <= $currentQtyInCart) {
            return back()->with('error', 'Maaf, stok tidak mencukupi!');
        }

        $finalPrice = ($product->is_promo && $product->discount_price)
                        ? $product->discount_price
                        : $product->price;

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'name'             => $product->name,
                'price'            => $finalPrice,
                'old_price'        => $product->price,
                'image'            => $product->image,
                'is_cod_available' => $product->is_cod_available ?? true,
                'quantity'         => 1,
            ];
        }

        session()->put('cart', $cart);

        return $request->input('redirect') === 'cart'
            ? redirect('/cart')
            : back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $data   = json_decode($request->getContent(), true) ?? $request->all();
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

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function checkout(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:30',
        'address' => 'required|string|max:1000',
        'payment_method' => 'required|string',
        'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
    ], [
        'payment_proof.mimes' => 'Format bukti pembayaran harus JPG, JPEG, PNG, atau WEBP.',
        'payment_proof.max' => 'Ukuran bukti pembayaran maksimal 5MB.',
    ]);

    try {
        return DB::transaction(function () use ($request) {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return response()->json(['success' => false, 'error' => 'Keranjang kosong.'], 400);
            }

            $proofPath = null;

            if ($request->hasFile('payment_proof')) {
                $proofPath = $this->uploadToCloudinary(
                    $request->file('payment_proof'),
                    'payment_proofs'
                );
            }

            $totalPriceServer = 0;
            $itemsString = '';

            foreach ($cart as $id => $item) {
                $product = Product::find($id);
                if (!$product) continue;

                $currentPrice = ($product->is_promo && $product->discount_price > 0)
                    ? $product->discount_price
                    : $product->price;

                $totalPriceServer += ($currentPrice * $item['quantity']);
                $itemsString .= "- {$product->name} ({$item['quantity']}x) @ Rp " . number_format($currentPrice) . "\n";
            }

            $order = Order::create([
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                'customer_address' => $request->address,
                'payment_method' => $request->payment_method,
                'total_price' => $totalPriceServer,
                'status' => ($request->payment_method == 'cod') ? 'pending' : 'waiting_verification',
                'payment_proof' => $proofPath,
            ]);

            foreach ($cart as $id => $item) {
                $product = Product::lockForUpdate()->find($id);
                if (!$product) continue;

                $finalPrice = ($product->is_promo && $product->discount_price > 0)
                    ? $product->discount_price
                    : $product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $finalPrice,
                ]);

                $product->decrement('stock', $item['quantity']);
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
                ],
            ]);
        });
    } catch (\Throwable $e) {
        report($e);

        return response()->json([
            'success' => false,
            'error' => config('app.debug')
                ? $e->getMessage()
                : 'Checkout gagal. Pastikan data sudah benar dan bukti pembayaran berformat JPG/PNG/WEBP maksimal 5MB.',
        ], 500);
    }
    }
}