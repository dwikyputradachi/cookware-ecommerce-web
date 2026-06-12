<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function normalizeCurrency($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', (string) $value);

        return $digits === '' ? null : (int) $digits;
    }

    private function uploadToCloudinary($file)
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
            'folder' => 'products',
            'resource_type' => 'image',
            'transformation' => [
                [
                    'width' => 1200,
                    'height' => 1200,
                    'crop' => 'limit',
                    'quality' => 'auto:good',
                ],
            ],
        ]);

        return $result['secure_url'];
    }

    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $codAvailableProducts = Product::where('is_cod_available', true)->count();
        $lowStockProducts = Product::where('stock', '<', 5)->count();

        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $totalOrders = Order::where('status', 'completed')->count();

        $pendingOrders = Order::whereIn('status', [
            'pending',
            'waiting_verification',
        ])->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStock',
            'codAvailableProducts',
            'lowStockProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders'
        ));
    }

    public function indexProducts()
    {
        $products = Product::latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->merge([
            'price' => $this->normalizeCurrency($request->price),
            'discount_price' => $request->has('is_promo')
                ? $this->normalizeCurrency($request->discount_price)
                : null,
        ]);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'category'         => 'nullable|string|max:255',
            'image'            => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'video_url'        => 'nullable|url',
            'is_cod_available' => 'boolean',
            'is_promo'         => 'boolean',
            'discount_price' => $request->has('is_promo')
                ? 'required|numeric|min:0|lt:price'
                : 'nullable',
        ], [
            'image.mimes' => 'Format gambar produk harus JPG, JPEG, PNG, atau WEBP.',
            'image.max'   => 'Ukuran gambar produk maksimal 5MB.',
        ]);

        if ($request->hasFile('image')) {
            try {
                $validated['image'] = $this->uploadToCloudinary($request->file('image'));
            } catch (\Throwable $e) {
                report($e);

                return back()
                    ->withInput()
                    ->with('error', 'Upload gambar gagal. Pastikan format JPG/PNG/WEBP dan ukuran maksimal 5MB.');
            }
        }

        $validated['is_cod_available'] = $request->has('is_cod_available') ? 1 : 0;
        $validated['is_promo'] = $request->has('is_promo') ? 1 : 0;

        if (!$validated['is_promo']) {
            $validated['discount_price'] = null;
        }

        Product::create($validated);

        Cache::forget('nav_categories');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->merge([
            'price' => $this->normalizeCurrency($request->price),
            'discount_price' => $request->has('is_promo')
                ? $this->normalizeCurrency($request->discount_price)
                : null,
        ]);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'category'         => 'nullable|string|max:255',
            'image'            => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
            'video_url'        => 'nullable|url',
            'is_cod_available' => 'boolean',
            'is_promo'         => 'boolean',
            'discount_price'   => 'nullable|numeric|min:0|lt:price',
        ], [
            'image.mimes' => 'Format gambar produk harus JPG, JPEG, PNG, atau WEBP.',
            'image.max'   => 'Ukuran gambar produk maksimal 5MB.',
        ]);

        if ($request->hasFile('image')) {
            try {
                $validated['image'] = $this->uploadToCloudinary($request->file('image'));
            } catch (\Throwable $e) {
                report($e);

                return back()
                    ->withInput()
                    ->with('error', 'Upload gambar gagal. Pastikan format JPG/PNG/WEBP dan ukuran maksimal 5MB.');
            }
        }

        $validated['is_cod_available'] = $request->has('is_cod_available') ? 1 : 0;
        $validated['is_promo'] = $request->has('is_promo') ? 1 : 0;

        if (!$validated['is_promo']) {
            $validated['discount_price'] = null;
        }

        $product->update($validated);

        Cache::forget('nav_categories');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

        Cache::forget('nav_categories');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function settings()
    {
        $settings = Setting::pluck('value', 'settings_key')->all();

        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name'         => 'required|string|max:255',
            'operational_hours' => 'required|string|max:255',
            'whatsapp'          => 'required|string|max:20|regex:/^62[0-9]{8,15}$/',
            'email'             => 'required|email|max:255',
            'facebook_url'      => 'nullable|url|max:255',
            'instagram_url'     => 'nullable|url|max:255',
            'whatsapp_url'      => 'nullable|url|max:255',
            'tiktok_url'        => 'nullable|url|max:255',
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['settings_key' => $key],
                ['value' => $value]
            );
        }

        Cache::forget('site_settings');

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan footer berhasil diperbarui!');
    }

    public function indexPages()
    {
        Page::ensureDefaultPagesExist();

        $pageKeys = array_keys(Page::defaultPages());
        $orderCase = implode("', '", $pageKeys);

        $pages = Page::whereIn('slug', $pageKeys)
            ->orderByRaw("FIELD(slug, '$orderCase')")
            ->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function editPage(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function updatePage(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $page->update([
            'title'     => $validated['title'],
            'content'   => $validated['content'],
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Halaman berhasil diperbarui!');
    }

    public function orders(Request $request)
    {
        $query = Order::with('user')->latest();

        $archive = $request->get('archive', 'active');

        if ($archive === 'archived') {
            $query->whereNotNull('archived_at');
        } elseif ($archive === 'all') {
            // tampilkan semua
        } else {
            $query->whereNull('archived_at');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                if (ctype_digit($search)) {
                    $q->orWhere('id', $search);
                }

                $q->orWhere('customer_name', 'like', '%' . $search . '%')
                ->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function approveOrder($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $order = Order::with('items.product')
                    ->lockForUpdate()
                    ->findOrFail($id);

                if (!in_array($order->status, ['waiting_verification', 'pending'])) {
                    throw new \Exception('Order sudah diproses.');
                }

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('total_sold', $item->quantity);
                    }
                }

                $order->update([
                    'status' => 'completed',
                ]);
            });

            return back()->with('success', 'Pesanan berhasil disetujui.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', $e->getMessage());
        }
    }

    public function rejectOrder($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $order = Order::with('items.product')
                    ->lockForUpdate()
                    ->findOrFail($id);

                if (!in_array($order->status, ['waiting_verification', 'pending'])) {
                    throw new \Exception('Order sudah diproses.');
                }

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }

                $order->update([
                    'status' => 'cancelled',
                ]);
            });

            return back()->with('success', 'Pesanan berhasil ditolak dan stok dikembalikan.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', $e->getMessage());
        }
    }

   public function archiveOrder(Order $order)
    {
        if (!in_array($order->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'Pesanan yang masih pending/menunggu verifikasi tidak boleh diarsipkan.');
        }

        $order->update([
            'archived_at' => now(),
        ]);

        return back()->with('success', 'Pesanan berhasil diarsipkan.');
    }

    public function restoreOrder(Order $order)
    {
        $order->update([
            'archived_at' => null,
        ]);

        return back()->with('success', 'Pesanan berhasil dipulihkan dari arsip.');
    }
}