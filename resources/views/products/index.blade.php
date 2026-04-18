@extends('layouts.app')

@section('title', 'Katalog - Santo Cookware')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-7xl">
    
    {{-- Banner Promo --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-[2rem] p-8 mb-12 text-center shadow-xl shadow-blue-100 relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl md:text-3xl font-bold">Beli Cookware Lebih Mudah</h2>
            <p class="text-blue-100 mt-2 text-sm md:text-base opacity-90">Peralatan dapur berkualitas untuk kebutuhan harian Anda</p>
        </div>
        {{-- Dekorasi Abstract --}}
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-400/20 rounded-full blur-3xl"></div>
    </div>

    {{-- Filter & Search Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Katalog Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Menampilkan produk terbaik untuk dapur Anda</p>
        </div>

        {{-- Search Bar Modern --}}
        <form action="/" method="GET" class="relative w-full md:w-96">
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Cari produk impian Anda..." 
                   class="w-full pl-12 pr-12 py-4 bg-white border border-gray-100 rounded-2xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none text-sm font-medium">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="search" class="w-5 h-5"></i>
            </div>
            @if(request('search'))
                <a href="/" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-red-500 transition-colors">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Category Pills --}}
    <div class="flex items-center gap-3 overflow-x-auto pb-4 mb-10 no-scrollbar">
        @php
            $currentCat = request('category', 'Semua');
            $categories = ['Semua', 'Wajan', 'Panci', 'Spatula', 'Set Alat Masak'];
        @endphp

        @foreach($categories as $cat)
            <a href="/?category={{ $cat }}{{ request('search') ? '&search='.request('search') : '' }}" 
               class="px-8 py-3 rounded-2xl text-sm font-bold transition-all whitespace-nowrap border
               {{ ($currentCat == $cat) 
                  ? 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-200 scale-105' 
                  : 'bg-white text-gray-500 border-gray-100 hover:border-blue-200 hover:text-blue-600' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
        @forelse($products as $product)
        <a href="/products/{{ $product->id }}"
           class="group bg-white rounded-[2rem] border border-gray-100 hover:shadow-2xl hover:shadow-blue-900/5 transition-all duration-300 overflow-hidden block relative">
            
            {{-- Badge COD --}}
            @if($product->is_cod_available)
                <div class="absolute top-4 left-4 z-10 bg-white/90 backdrop-blur-md text-green-600 text-[10px] font-bold px-3 py-1.5 rounded-xl shadow-sm border border-green-100 flex items-center gap-1">
                    <i data-lucide="truck" class="w-3 h-3"></i> COD
                </div>
            @endif

            {{-- Image Container --}}
            <div class="relative h-48 md:h-56 overflow-hidden bg-gray-50">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-blue-200">
                        <i data-lucide="cooking-pot" class="w-12 h-12 mb-2 opacity-20"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest">Santo Cook</span>
                    </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="p-5">
                <h2 class="text-sm md:text-base font-bold text-gray-800 line-clamp-2 group-hover:text-blue-600 transition-colors leading-snug h-10 md:h-12">
                    {{ $product->name }}
                </h2>
                
                <div class="mt-4 flex flex-col gap-1">
                    <p class="text-lg font-black text-blue-600">Rp {{ number_format($product->price) }}</p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider {{ $product->stock > 0 ? 'text-green-500' : 'text-red-400' }}">
                            {{ $product->stock > 0 ? $product->stock . ' Stok Ready' : 'Habis' }}
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @empty
        {{-- State Jika Produk Kosong --}}
        <div class="col-span-full py-24 text-center">
            <div class="bg-blue-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="search-x" class="w-12 h-12 text-blue-300"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 mt-2">Maaf Twin, produk "{{ request('search') }}" belum tersedia di Santo Cookware.</p>
            <a href="/" class="mt-6 inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Reset Pencarian
            </a>
        </div>
        @endforelse
    </div>

</div>
@endsection