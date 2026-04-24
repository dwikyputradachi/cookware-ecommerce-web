<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $codAvailableProducts = Product::where('is_cod_available', true)->count();
        $lowStockProducts = Product::where('stock', '<', 5)->count();
        
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_price');

        return view('admin.dashboard', compact(
            'totalProducts', 'totalStock', 'codAvailableProducts', 
            'lowStockProducts', 'totalOrders', 'totalRevenue'
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

        if ($request->hasFile('image')) {
            // Disimpan di: storage/app/public/products
            // $imagePath akan berisi: products/namafile.jpg
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_cod_available'] = $request->has('is_cod_available') ? 1 : 0;

        Product::create($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

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

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_cod_available'] = $request->has('is_cod_available') ? 1 : 0;

        $product->update($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyProduct(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil dihapus!');
    }
}