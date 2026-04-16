<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - Santo Cookware</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">

@php $cartCount = count(session('cart', [])); @endphp

<nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <a href="/" class="text-xl font-semibold text-blue-600">Santo Cookware</a>
        <form action="/cart/add/{{ $product->id }}" method="POST">
            @csrf
            <button type="submit" class="mt-3 w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 rounded-xl transition">
                + Keranjang
            </button>
        </form>
    </div>
</nav>

<div class="container mx-auto px-4 py-8 max-w-4xl">

    <a href="/products" class="text-sm text-blue-500 hover:underline mb-4 inline-block">← Kembali</a>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden md:flex">

        <img src="{{ $product->image ?? 'https://via.placeholder.com/400x400' }}"
             class="w-full md:w-80 h-72 md:h-auto object-cover">

        <div class="p-6 flex flex-col justify-between flex-1">
            <div>
                <h1 class="text-xl font-semibold text-gray-800">{{ $product->name }}</h1>
                <p class="text-2xl font-bold text-blue-600 mt-2">Rp {{ number_format($product->price) }}</p>

                @if($product->is_cod_available)
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded-full mt-2 inline-block">COD tersedia</span>
                @endif

                <p class="text-gray-500 text-sm mt-4 leading-relaxed">{{ $product->description }}</p>
                <p class="text-sm mt-3 {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                    Stok: {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                </p>
            </div>

            @if($product->stock > 0)
            <form action="/cart/add/{{ $product->id }}" method="POST" class="mt-6">
                @csrf
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-medium transition">
                    + Tambah ke Keranjang
                </button>
            </form>
            @endif
        </div>
    </div>

</div>
</body>
</html>