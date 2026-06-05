<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Page;
use App\Models\Setting;
use Cloudinary\Cloudinary;

class AdminController extends Controller
{
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

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStock',
            'codAvailableProducts',
            'lowStockProducts',
            'totalOrders',
            'totalRevenue'
        ));
    }

    public function indexProducts()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request)
    {
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

        Product::create($validated);

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

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

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

    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function approveOrder($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        if ($order->status !== 'waiting_verification') {
            return back()->with('error', 'Order sudah diproses');
        }

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('total_sold', $item->quantity);
            }
        }

        $order->update(['status' => 'completed']);

        return back()->with('success', 'Pesanan berhasil disetujui');
    }

    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'waiting_verification') {
            return back()->with('error', 'Order sudah diproses');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan berhasil ditolak');
    }
}