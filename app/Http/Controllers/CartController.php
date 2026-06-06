<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentSetting;
use App\Models\Product;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

            // Resize + compress ringan.
            // Tujuannya supaya gambar bukti pembayaran tidak terlalu berat,
            // tapi tetap cukup jelas untuk dicek admin.
            'transformation' => [
                [
                    'width' => 1600,
                    'height' => 1600,
                    'crop' => 'limit',
                    'quality' => 'auto:good',
                ],
            ],
        ]);

        return $result['secure_url'];
    }

    public function index()
    {
        $cart = session()->get('cart', []);

        $cart = array_filter($cart, function ($item) {
            return isset($item['quantity']) && $item['quantity'] > 0;
        });

        session()->put('cart', $cart);

        // Cache payment method aktif supaya tidak query database terus setiap halaman cart dibuka.
        $payments = Cache::remember('active_payment_settings', now()->addMinutes(10), function () {
            return PaymentSetting::where('is_active', true)
                ->orderBy('id')
                ->get();
        });

        return view('cart.index', compact('payments', 'cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        foreach ($cart as $key => $item) {
            if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                unset($cart[$key]);
            }
        }

        $currentQtyInCart = isset($cart[$id]) ? (int) $cart[$id]['quantity'] : 0;

        if ($product->stock <= $currentQtyInCart) {
            return back()->with('error', 'Maaf, stok tidak mencukupi!');
        }

        $finalPrice = ($product->is_promo && $product->discount_price > 0)
            ? $product->discount_price
            : $product->price;

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            $cart[$id]['price'] = $finalPrice;
            $cart[$id]['old_price'] = $product->price;
            $cart[$id]['image'] = $product->image;
            $cart[$id]['is_cod_available'] = $product->is_cod_available ?? true;
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

        if (!isset($cart[$id])) {
            return response()->json([
                'success' => false,
                'error' => 'Produk tidak ditemukan di keranjang.',
            ], 404);
        }

        $data = json_decode($request->getContent(), true) ?? $request->all();
        $action = $data['action'] ?? null;

        if ($action === 'plus') {
            $product = Product::find($id);

            if (!$product) {
                unset($cart[$id]);
                session()->put('cart', $cart);

                return response()->json([
                    'success' => false,
                    'error' => 'Produk sudah tidak tersedia.',
                    'cart' => $cart,
                ], 404);
            }

            if ($product->stock > $cart[$id]['quantity']) {
                $cart[$id]['quantity']++;

                $finalPrice = ($product->is_promo && $product->discount_price > 0)
                    ? $product->discount_price
                    : $product->price;

                $cart[$id]['price'] = $finalPrice;
                $cart[$id]['old_price'] = $product->price;
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Stok produk tidak mencukupi.',
                    'cart' => $cart,
                ], 400);
            }
        }

        if ($action === 'minus') {
            $cart[$id]['quantity']--;

            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
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
            'cart' => $cart,
        ]);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:30',
            'address'        => 'required|string|max:1000',
            'payment_method' => 'required|string',
            'payment_proof'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'payment_proof.mimes' => 'Format bukti pembayaran harus JPG, JPEG, PNG, atau WEBP.',
            'payment_proof.max'   => 'Ukuran bukti pembayaran maksimal 5MB.',
        ]);

        try {
            $cart = session()->get('cart', []);

            if (empty($cart)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Keranjang kosong.',
                ], 400);
            }

            $payment = PaymentSetting::where('payment_key', $validated['payment_method'])
                ->where('is_active', true)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'error' => 'Metode pembayaran tidak valid atau sedang nonaktif.',
                ], 400);
            }

            $isCod = $payment->payment_key === 'cod';

            if (!$isCod && !$request->hasFile('payment_proof')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Bukti pembayaran wajib diupload untuk metode pembayaran ini.',
                ], 422);
            }

            $proofPath = null;

            // Upload dilakukan sebelum DB transaction supaya database tidak terlalu lama terkunci.
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');

                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'error' => $file->getErrorMessage(),
                    ], 422);
                }

                $proofPath = $this->uploadToCloudinary($file, 'payment_proofs');
            }

            return DB::transaction(function () use ($validated, $cart, $payment, $isCod, $proofPath) {
                $totalPriceServer = 0;
                $itemsString = '';
                $preparedItems = [];

                foreach ($cart as $id => $item) {
                    $quantity = (int) ($item['quantity'] ?? 0);

                    if ($quantity <= 0) {
                        continue;
                    }

                    $product = Product::lockForUpdate()->find($id);

                    if (!$product) {
                        throw new \Exception('Ada produk di keranjang yang sudah tidak tersedia.');
                    }

                    if ($product->stock < $quantity) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.");
                    }

                    if ($isCod && !($product->is_cod_available ?? true)) {
                        throw new \Exception("Produk {$product->name} tidak mendukung pembayaran COD.");
                    }

                    $finalPrice = ($product->is_promo && $product->discount_price > 0)
                        ? $product->discount_price
                        : $product->price;

                    $totalPriceServer += $finalPrice * $quantity;

                    $itemsString .= "- {$product->name} ({$quantity}x) @ Rp " . number_format($finalPrice) . "\n";

                    $preparedItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $finalPrice,
                    ];
                }

                if (empty($preparedItems)) {
                    throw new \Exception('Produk di keranjang tidak valid.');
                }

                $order = Order::create([
                    'customer_name'    => $validated['name'],
                    'customer_phone'   => $validated['phone'],
                    'customer_address' => $validated['address'],
                    'payment_method'   => $payment->payment_key,
                    'total_price'      => $totalPriceServer,
                    'status'           => $isCod ? 'pending' : 'waiting_verification',
                    'payment_proof'    => $proofPath,
                ]);

                foreach ($preparedItems as $prepared) {
                    $product = $prepared['product'];
                    $quantity = $prepared['quantity'];
                    $price = $prepared['price'];

                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $product->id,
                        'quantity'   => $quantity,
                        'price'      => $price,
                    ]);

                    $product->decrement('stock', $quantity);
                }

                session()->forget('cart');

                return response()->json([
                    'success'      => true,
                    'order_id'     => $order->id,
                    'items_string' => $itemsString,
                    'data_server'  => [
                        'name'           => $order->customer_name,
                        'phone'          => $order->customer_phone,
                        'address'        => $order->customer_address,
                        'total_price'    => number_format($order->total_price, 0, ',', '.'),
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
                    : 'Checkout gagal. Pastikan data sudah benar dan stok produk masih tersedia.',
            ], 500);
        }
    }
}