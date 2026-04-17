@extends('layouts.app')

@section('title', $product->name . ' - Santo Cookware')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">

    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="/" class="hover:text-blue-600 transition-colors">Katalog</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium truncate">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm flex flex-col md:flex-row">
        
        <div class="w-full md:w-1/2 relative bg-gray-50 p-4 flex items-center justify-center">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/600x600' }}"
                 class="w-full h-[350px] md:h-[500px] object-cover rounded-[2rem] shadow-inner transition-transform hover:scale-105 duration-500">
            
            @if($product->is_cod_available)
                <div class="absolute top-8 left-8 bg-green-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                    <i data-lucide="truck" class="w-3 h-3"></i> BISA COD
                </div>
            @endif
        </div>

        <div class="p-8 md:p-12 flex flex-col flex-1">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
                
                <div class="flex items-center gap-4 mt-4">
                    <p class="text-3xl font-extrabold text-blue-600">Rp {{ number_format($product->price) }}</p>
                    <div class="h-6 w-[1px] bg-gray-200"></div>
                    <p class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }} font-semibold uppercase tracking-wider">
                        {{ $product->stock > 0 ? 'Tersedia: ' . $product->stock : 'Stok Habis' }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-8 py-6 border-y border-gray-50">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-600">Kualitas Premium</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                            <i data-lucide="award" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-600">Original Santo</span>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-3">Deskripsi Produk</h3>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>
            </div>

            @if($product->stock > 0)
            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                <form action="/cart/add/{{ $product->id }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="redirect" value="back">
                    <button class="w-full flex items-center justify-center gap-2 border-2 border-blue-600 text-blue-600 hover:bg-blue-50 py-4 rounded-2xl font-bold transition-all active:scale-[0.97]">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        + Keranjang
                    </button>
                </form>

                <form action="/cart/add/{{ $product->id }}" method="POST" class="flex-[1.5]">
                    @csrf
                    <input type="hidden" name="redirect" value="cart">
                    <button class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-2xl font-bold shadow-xl shadow-blue-200 transition-all active:scale-[0.97]">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                        Beli Sekarang
                    </button>
                </form>
            </div>
            @else
                <div class="mt-10 bg-gray-50 border border-gray-100 text-gray-400 text-center py-5 rounded-2xl font-medium">
                    Mohon Maaf, Produk Sedang Habis
                </div>
            @endif
        </div>
    </div>

    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div class="p-6">
            <i data-lucide="message-square" class="w-8 h-8 text-blue-500 mx-auto mb-3"></i>
            <h4 class="font-bold text-gray-800">Konsultasi Gratis</h4>
            <p class="text-sm text-gray-500 mt-1">Bingung pilih alat masak? Chat kami di WhatsApp.</p>
        </div>
        <div class="p-6">
            <i data-lucide="package-check" class="w-8 h-8 text-blue-500 mx-auto mb-3"></i>
            <h4 class="font-bold text-gray-800">Packing Aman</h4>
            <p class="text-sm text-gray-500 mt-1">Setiap pengiriman dilapisi bubble wrap tebal.</p>
        </div>
        <div class="p-6">
            <i data-lucide="credit-card" class="w-8 h-8 text-blue-500 mx-auto mb-3"></i>
            <h4 class="font-bold text-gray-800">Bayar di Tempat</h4>
            <p class="text-sm text-gray-500 mt-1">Cek barang dulu baru bayar (untuk produk COD).</p>
        </div>
    </div>

</div>
@endsection