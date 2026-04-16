{{-- resources/views/products/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Santo Cookware</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

@php $cartCount = count(session('cart', [])); @endphp

<nav class="bg-blue-500 border-b border-gray-100 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <a href="/" class="text-xl font-semibold text-white">Santo Cookware</a>
        <div class="hidden md:flex gap-6 text-sm text-gray-300">
            <a href="/" class="hover:text-white">Home</a>
            <a href="/products" class="hover:text-white">Produk</a>
        </div>
        <div class="flex items-center gap-3">
            <a href="/cart" class="relative">
                <span class="text-2xl">🛒</span>
                @if($cartCount > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 py-8">

    <div class="bg-blue-600 text-white rounded-2xl p-6 mb-8 text-center">
        <h2 class="text-xl font-semibold">Beli Cookware Lebih Mudah</h2>
        <p class="text-sm mt-1 text-blue-100">Peralatan dapur berkualitas untuk kebutuhan harian</p>
    </div>

    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Katalog Produk</h1>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($products as $product)
        <div class="bg-white rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <a href="/products/{{ $product->id }}">
                <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}"
                     class="w-full h-44 object-cover hover:scale-105 transition duration-300">
            </a>
            <div class="p-3">
                <a href="/products/{{ $product->id }}">
                    <h2 class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-blue-600">{{ $product->name }}</h2>
                </a>
                <p class="text-blue-600 font-semibold mt-1">Rp {{ number_format($product->price) }}</p>
                @if($product->is_cod_available)
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full">COD</span>
                @endif
                <form action="/cart/add/{{ $product->id }}" method="POST">
                    @csrf
                    <button class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 rounded-xl transition">
                        + Keranjang
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

</div>
</body>
</html>