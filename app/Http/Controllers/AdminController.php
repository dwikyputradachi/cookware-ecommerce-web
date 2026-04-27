<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

class AdminController extends Controller
{
    /**
     * Show Admin Dashboard
     */
    public function dashboard()
    {
        // Product Statistics
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $codAvailableProducts = Product::where('is_cod_available', true)->count();
        $lowStockProducts = Product::where('stock', '<', 5)->count();
        
        // Order Statistics
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price');

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'is_cod_available' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'is_cod_available' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_cod_available'] = $request->has('is_cod_available') ? 1 : 0;

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
        $order = Order::findOrFail($id);

        // Cegah double approval
        if ($order->status !== 'waiting_verification') {
            return back()->with('error', 'Order sudah diproses');
        }

        $order->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Pesanan berhasil disetujui');
    }

    public function rejectOrder($id)
    {
        $order = Order::findOrFail($id);

        // Cegah double rejection
        if ($order->status !== 'waiting_verification') {
            return back()->with('error', 'Order sudah diproses');
        }

        $order->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Pesanan berhasil ditolak');
    }

}

