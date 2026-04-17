@extends('layouts.app')

@section('title', 'Katalog - Santo Cookware')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="bg-blue-600 text-white rounded-2xl p-6 mb-8 text-center">
        <h2 class="text-xl font-semibold">Beli Cookware Lebih Mudah</h2>
        <p class="text-sm mt-1 text-blue-100">Peralatan dapur berkualitas untuk kebutuhan harian</p>
    </div>

    <h1 class="text-2xl font-semibold mb-6 text-gray-800">Katalog Produk</h1>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($products as $product)
        <a href="/products/{{ $product->id }}"
           class="bg-white rounded-2xl border border-gray-100 hover:shadow-md transition-shadow duration-200 overflow-hidden block">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}"
                 class="w-full h-44 object-cover">
            <div class="p-3">
                <h2 class="text-sm font-medium text-gray-800 line-clamp-2">{{ $product->name }}</h2>
                <p class="text-blue-600 font-semibold mt-1">Rp {{ number_format($product->price) }}</p>
                <p class="text-sm mt-3 {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }}">
                    Stok: {{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}
                </p>
                @if($product->is_cod_available)
                    <span class="text-xs bg-green-50 text-green-700 px-2 py-0.5 rounded-full mt-1 inline-block">COD</span>
                @endif
            </div>
        </a>
        @endforeach
    </div>

</div>
@endsection