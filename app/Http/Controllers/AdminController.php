<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    /**
     * Upload image to Cloudinary and return secure URL
     */
    private function uploadToCloudinary($file)
    {
        $cloudinary = new \Cloudinary\Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);

        $result = $cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => 'products'
        ]);

        return $result['secure_url'];
    }

    /**
     * Show Admin Dashboard
     */
    public function dashboard()
    {
        $totalProducts      = Product::count();
        $totalStock         = Product::sum('stock');
        $codAvailableProducts = Product::where('is_cod_available', true)->count();
        $lowStockProducts   = Product::where('stock', '<', 5)->count();
        $totalRevenue       = Order::where('status', 'completed')->sum('total_price');
        $totalOrders        = Order::where('status', 'completed')->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalStock',
            'codAvailableProducts',
            'lowStockProducts',
            'totalOrders',
            'totalRevenue'
        ));
    }

    /**
     * Display all products for management
     */
    public function indexProducts()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show create product form
     */
    public function createProduct()
    {
        return view('admin.products.create');
    }

    /**
     * Store new product
     */
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'category'       => 'nullable|string|max:255',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url'      => 'nullable|url',
            'is_cod_available' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $validated['is_cod_available'] = $request->has('is_cod_available') ? 1 : 0;

        Product::create($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show edit product form
     */
    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock'            => 'required|integer|min:0',
            'category'         => 'nullable|string|max:255',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url'        => 'nullable|url',
            'is_cod_available' => 'boolean',
            'is_promo'         => 'boolean',
            'discount_price'   => 'nullable|numeric|min:0|lt:price',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->uploadToCloudinary($request->file('image'));
        }

        $validated['is_cod_available'] = $request->has('is_cod_available') ? 1 : 0;
        $validated['is_promo']         = $request->has('is_promo') ? 1 : 0;

        if (!$validated['is_promo']) {
            $validated['discount_price'] = null;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Delete product
     */
    public function destroyProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus!');
    }

    // ORDER MANAGEMENT

    public function orders()
    {
        $orders = Order::latest()->get();
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
            $item->product->decrement('stock', $item->quantity);
            $item->product->increment('total_sold', $item->quantity);
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