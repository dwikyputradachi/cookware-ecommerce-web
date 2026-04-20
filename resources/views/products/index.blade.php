@extends('layouts.app')
@section('title', 'Katalog - Murazon Cookware') {{-- Nama Update --}}
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    /* Gunakan variabel CSS yang sudah kita buat sebelumnya */
    .swiper-pagination-bullet-active { background: var(--color-secondary) !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">
    
    <div class="container mx-auto px-4 pt-6">
        <div class="swiper mainSwiper rounded-3xl overflow-hidden shadow-2xl shadow-orange-900/10">
            <div class="swiper-wrapper">
                @forelse($banners as $banner)
                    <div class="swiper-slide relative">
                        <a href="{{ $banner->link ?? '#' }}">
                            <img src="{{ asset('storage/' . $banner->image) }}" class="w-full h-48 md:h-100 object-cover">
                        </a>
                    </div>
                @empty
                    {{-- Placeholder Tema Murazon --}}
                    <div class="swiper-slide relative">
                        <div class="bg-linear-to-r from-[#6B3005] to-[#E1700F] p-6 md:p-10 text-white min-h-48 md:min-h-100 flex items-center overflow-hidden">
                            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6 w-full">
                                <div class="text-center md:text-left">
                                    <span class="bg-white/20 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">Kualitas Premium</span>
                                    <h2 class="text-2xl md:text-4xl font-black mt-3 leading-tight uppercase">Masak Lebih Nikmat <br>Bersama Murazon</h2>
                                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-6">
                                        <div class="text-center">
                                            <p class="text-xl md:text-2xl font-bold">100%</p>
                                            <p class="text-[10px] uppercase opacity-80">Original</p>
                                        </div>
                                        <div class="w-px h-8 bg-white/20 self-center"></div>
                                        <div class="text-center">
                                            <p class="text-xl md:text-2xl font-bold">Terpercaya</p>
                                            <p class="text-[10px] uppercase opacity-80">Garansi Produk</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- Logo Murazon ditaruh di sini sebagai elemen branding --}}
                                <div class="hidden md:block">
                                    <img src="{{ asset('img/logo-murazon.png') }}" class="w-48 opacity-20 brightness-0 invert">
                                </div>
                            </div>
                            <div class="absolute -top-10 -right-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="container mx-auto px-4 mt-8">
        {{-- 2. Grid Kategori (Warna Border & Text Update) --}}
        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-8 gap-y-8 gap-x-4 mb-12">
            @foreach($categories as $cat)
            <a href="/?category={{ $cat['name'] }}" class="flex flex-col items-center group">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white border-2 {{ request('category') == $cat['name'] ? 'border-[#E1700F] shadow-lg shadow-orange-100' : 'border-gray-50' }} flex items-center justify-center transition-all duration-300 group-hover:scale-105 p-2 overflow-hidden">
                   <img src="{{ asset('img/categories/' . $cat['img']) }}" 
                    alt="{{ $cat['name'] }}" 
                    onerror="this.src='{{ asset('img/categories/default.png') }}'"
                    class="w-full h-full object-contain">
                </div>
                <span class="text-[10px] md:text-[11px] font-bold mt-3 text-center leading-tight tracking-tight {{ request('category') == $cat['name'] ? 'text-[#E1700F]' : 'text-gray-500' }}">
                    {{ $cat['name'] }}
                </span>
            </a>
            @endforeach

            <a href="/" class="flex flex-col items-center group">
                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gray-100 flex items-center justify-center transition-all duration-300 group-hover:bg-gray-200">
                    <i data-lucide="layout-grid" class="w-6 h-6 text-gray-400"></i>
                </div>
                <span class="text-[10px] md:text-[11px] font-bold mt-3 text-gray-500">Semua</span>
            </a>
        </div>

        {{-- 3. Info Bar --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4 border-b border-gray-100 pb-6">
            <h3 class="text-xl font-black text-gray-800 tracking-tight">Koleksi Produk</h3>
            <div class="hidden md:flex items-center gap-6">
                <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                    <i data-lucide="check-circle" class="w-4 h-4 text-[#E1700F]"></i> BARANG ORI
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                    <i data-lucide="truck" class="w-4 h-4 text-orange-400"></i> PENGIRIMAN AMAN
                </div>
            </div>
        </div>

        {{-- 4. Grid Produk (Warna Harga & Aksen Update) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
            @forelse($products as $product)
            <a href="/products/{{ $product->id }}" class="group bg-white rounded-3xl border border-gray-100 hover:shadow-2xl hover:shadow-orange-900/5 transition-all duration-300 overflow-hidden flex flex-col relative">
                @if($product->is_cod_available)
                    <div class="absolute top-3 left-3 z-10 bg-[#E1700F] text-white text-[9px] font-black px-2.5 py-1 rounded-lg shadow-sm flex items-center gap-1 uppercase">
                        <i data-lucide="truck" class="w-3 h-3"></i> Bisa COD
                    </div>
                @endif
                <div class="h-44 md:h-64 bg-gray-50 overflow-hidden flex items-center justify-center p-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
                    @else
                        <i data-lucide="cooking-pot" class="w-16 h-16 text-orange-100"></i>
                    @endif
                </div>
                <div class="p-4 md:p-6 flex flex-col grow">
                    <h2 class="text-sm md:text-base font-bold text-gray-800 line-clamp-2 h-10 md:h-12 group-hover:text-[#E1700F] transition-colors">
                        {{ $product->name }}
                    </h2>
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Harga Terbaik</p>
                        <p class="text-lg md:text-xl font-black text-[#E1700F]">Rp {{ number_format($product->price) }}</p>
                    </div>
                    <div class="mt-3 flex items-center gap-2">
                        <div class="grow bg-gray-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-[#E1700F] h-full w-3/4"></div>
                        </div>
                        <span class="text-[9px] font-black text-gray-400 italic">STOK READY</span>
                    </div>
                </div>
            </a>
            @empty
                <div class="col-span-full py-20 text-center">
                    <h3 class="text-lg font-bold text-gray-400 uppercase">Produk Belum Tersedia</h3>
                </div>
            @endforelse
        </div>
    </div>
</div>
...
@endsection