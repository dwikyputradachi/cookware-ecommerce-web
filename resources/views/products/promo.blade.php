@extends('layouts.app')

@section('title', 'Promo Spesial - Murazon Cookware')

@push('styles')
<style>
    /* Pakai variabel warna yang sama dengan dashboardmu */
    :root {
        --primary: #E1700F;
        --primary-dark: #4c2203;
    }

    /* Efek Hero Khusus Promo - Kita pakai nuansa Merah-Oranye biar terkesan 'Urgent' */
    .hero-promo {
        background: linear-gradient(135deg, #7f1d1d 0%, #a1500a 50%, var(--primary) 100%);
        position: relative;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">

    {{-- 1. HERO SECTION PROMO --}}
    <div class="container mx-auto px-4 pt-8">
        {{-- Luna tambahkan 'bg-no-repeat bg-cover' dan pastikan background gradasi di inline style --}}
        <div class="rounded-[3rem] p-10 md:p-16 text-white shadow-2xl border-4 border-white relative overflow-hidden bg-no-repeat bg-cover"
            style="background: linear-gradient(135deg, #7f1d1d 0%, #a1500a 50%, #E1700F 100%);">
            
            {{-- Efek Glow Decorative - Luna naikin opacity-nya dikit biar makin kelihatan --}}
            <div class="absolute w-75 h-75 rounded-full blur-[80px] opacity-40 -bottom-10 -left-10 pointer-events-none" 
                style="background: radial-gradient(circle, #fca5a5 0%, transparent 70%);"></div>

            <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/20 backdrop-blur-md border border-white/20 shadow-inner">
                    <i data-lucide="percent" class="w-4 h-4 text-red-300"></i>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-orange-100">
                        Penawaran Terbatas
                    </span>
                </div>

                <h1 class="text-4xl md:text-6xl font-black italic uppercase leading-none tracking-tighter text-white">
                    Flash <span class="text-red-300">Sale</span> <br> 
                    Ramadan <span style="color: #FDBA74;">Deals</span>
                </h1>

                <div class="p-4 rounded-2xl bg-black/10 backdrop-blur-sm border border-white/5 max-w-lg mx-auto">
                    <p class="text-orange-50 text-sm md:text-base font-medium opacity-90 leading-relaxed">
                        Upgrade dapurmu dengan koleksi cookware terbaik. Diskon spesial untuk produk pilihan hanya sampai akhir bulan ini!
                    </p>
                </div>

                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-orange-200">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    <span>Selama Persediaan Masih Ada</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. LIST PRODUK PROMO --}}
    <div class="container mx-auto px-4 mt-16">
        {{-- Header Section --}}
        <div class="mb-10 border-b-2 border-gray-100 pb-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-black text-gray-900 italic uppercase tracking-tighter flex items-center gap-3">
                    <i data-lucide="zap" class="w-6 h-6 text-red-600"></i>
                    Barang Diskon Hari Ini
                </h3>
                <p class="text-gray-400 text-xs font-bold uppercase mt-1">Menampilkan {{ $products->count() }} Produk Pilihan</p>
            </div>
            
            {{-- Shortcut ke Kategori --}}
            <div class="flex gap-2 overflow-x-auto no-scrollbar pb-2">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category['name']]) }}" 
                       class="px-4 py-2 bg-white border border-gray-100 rounded-full text-[10px] font-bold uppercase whitespace-nowrap hover:border-[#E1700F] hover:text-[#E1700F] transition">
                        {{ $category['name'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-8">
            @forelse($products as $product)
                {{-- Memanggil komponen sakti kita --}}
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full py-32 text-center bg-white rounded-[3rem] border border-gray-100 shadow-sm">
                    <div class="text-gray-200 mb-6 flex justify-center">
                        <i data-lucide="ticket-percent" class="w-20 h-20 stroke-1"></i>
                    </div>
                    <p class="text-gray-400 font-bold uppercase italic tracking-widest text-lg">Belum ada promo aktif...</p>
                    <a href="{{ route('products.index') }}" class="mt-4 inline-block text-[#E1700F] font-extrabold text-sm uppercase underline decoration-2 underline-offset-4">
                        Lihat Semua Koleksi
                    </a>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection