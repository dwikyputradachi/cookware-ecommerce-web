@extends('layouts.app')

@section('title', 'Katalog - Murazon Cookware')

@push('styles')
{{-- Font Khusus untuk kesan Premium (Opsional, pastikan terhubung di app.blade.php atau buka komen ini) --}}
{{-- <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,900&display=swap" rel="stylesheet"> --}}

<style>
    :root {
        --primary: #E1700F;
        --primary-dark: #6B3005;
        --accent: #FDBA74; /* orange-300 */
    }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Efek Hover Card ala E-commerce Premium */
    .product-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, box-shadow;
    }

    .product-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 25px 50px -12px rgba(107, 48, 5, 0.15);
        border-color: rgba(225, 112, 15, 0.2);
    }

    /* --- HIASAN SIGNATURE HERO SECTION --- */
    .hero-signature {
        background: linear-gradient(135deg, #4c2203 0%, #a1500a 40%, var(--primary) 100%);
        position: relative;
        overflow: hidden;
    }

    /* Pola Abstrak Halus di Background */
    .hero-signature::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.5;
    }

    /* Efek Cahaya Sorot (Glow) */
    .hero-glow {
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(253, 186, 116, 0.15) 0%, rgba(225, 112, 15, 0) 70%);
        border-radius: 50%;
        top: -100px;
        right: -100px;
        pointer-events: none;
    }

    /* Styling Teks Khususs */
    .font-premium-italic {
        font-family: 'Playfair Display', serif; /* Gunakan font serif jika tersedia */
        font-weight: 900;
        font-style: italic;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">

    {{-- 1. PENGGANTI BANNER: SIGNATURE HERO SECTION --}}
    <div class="container mx-auto px-4 pt-8">
        {{-- Warnanya aku kunci pakai HEX biar gak jadi putih --}}
        <div class="rounded-[3rem] p-10 md:p-20 text-white shadow-2xl border-4 border-white relative overflow-hidden" 
             style="background: linear-gradient(135deg, #4c2203 0%, #a1500a 40%, #E1700F 100%);">
            
            {{-- Efek Glow Decorative --}}
            <div class="absolute w-[400px] h-[400px] rounded-full blur-[100px] opacity-20 -top-20 -right-20 pointer-events-none" 
                 style="background: radial-gradient(circle, #FDBA74 0%, transparent 70%);"></div>
            
            <div class="relative z-10 grid md:grid-cols-2 gap-10 items-center">
                
                {{-- KOLOM TEKS --}}
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/20 backdrop-blur-md border border-white/10 shadow-inner">
                        <i data-lucide="crown" class="w-4 h-4 text-orange-300"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-orange-100">
                            The Culinary Standard
                        </span>
                    </div>

                    <h2 class="text-4xl md:text-7xl font-black italic uppercase leading-[0.9] tracking-tighter text-white">
                        Authentic<br> 
                        <span style="color: #FDBA74;">Cookware</span>
                    </h2>

                    {{-- Kotak Deskripsi dengan background agak gelap biar teks putihnya JELAS --}}
                    <div class="p-5 rounded-2xl bg-black/20 backdrop-blur-sm border border-white/5 shadow-lg max-w-xl">
                        <p class="text-orange-50 text-sm md:text-lg font-medium opacity-90 leading-relaxed">
                            Mewujudkan kelezatan bintang lima di dapur Anda. Didesain dengan presisi, material premium, dan durabilitas tanpa kompromi untuk performa masak terbaik.
                        </p>
                    </div>

                    {{-- Tombol --}}
                    <div class="pt-4 flex flex-wrap gap-4 items-center">
                        <a href="#koleksi" class="px-8 py-3.5 bg-white text-gray-950 rounded-full font-extrabold text-sm uppercase tracking-wider shadow-lg hover:bg-orange-50 transition-all flex items-center gap-2 group">
                            Jelajahi Produk
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <span class="text-xs text-orange-200 font-bold tracking-wide uppercase">Garansi Seumur Hidup*</span>
                    </div>
                </div>

                {{-- KOLOM VISUAL --}}
                <div class="relative flex justify-center md:justify-end">
                    <div class="relative z-10 transform md:translate-x-10 md:rotate-6">
                        {{-- Warna ikon aku ganti ke orange terang biar kontras --}}
                        <i data-lucide="cooking-pot" class="w-48 h-48 md:w-80 md:h-80 text-orange-300/30 stroke-[1.5]"></i>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    {{-- 2. KATEGORI --}}
    <div class="container mx-auto px-4 mt-16">
        <div class="mb-8 border-b border-gray-100 pb-4 flex items-center justify-between">
            <h3 class="text-xl font-black text-gray-800 uppercase italic tracking-tight flex items-center gap-3">
                <i data-lucide="layout-grid" class="w-5 h-5 text-[#E1700F]"></i>
                Kategori Pilihan
            </h3>
        </div>

        <div class="flex gap-5 overflow-x-auto no-scrollbar pb-6 snap-x">
            @foreach($categories as $cat)
            <a href="/?category={{ $cat['name'] }}" class="flex-none w-28 snap-start group text-center">
                <div class="w-24 h-24 mx-auto rounded-[2rem] bg-white border-2 {{ request('category') == $cat['name'] ? 'border-[#E1700F] shadow-lg shadow-orange-100' : 'border-gray-100' }} flex items-center justify-center transition-all group-hover:border-orange-300 p-4 shadow-sm group-hover:shadow-md">
                    <img src="{{ asset('img/categories/' . $cat['img']) }}" 
                         class="w-full h-full object-contain group-hover:scale-105 transition-transform" 
                         onerror="this.src='{{ asset('img/categories/default.png') }}'">
                </div>
                <span class="text-xs font-bold mt-3 block {{ request('category') == $cat['name'] ? 'text-[#E1700F]' : 'text-gray-600' }}">
                    {{ $cat['name'] }}
                </span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- 3. HOT ITEM --}}
    <div class="container mx-auto px-4 mt-12">
        <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-orange-100 shadow-sm relative overflow-hidden">
            {{-- Hiasan Pojok --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-red-50 rounded-bl-full opacity-60"></div>

            <div class="flex items-center gap-4 mb-10 relative z-10">
                <div class="bg-red-600 text-white px-4 py-1.5 rounded-full font-black text-xs uppercase italic animate-pulse flex items-center gap-1.5 shadow-lg shadow-red-200">
                    <i data-lucide="zap" class="w-3 h-3"></i> Hot Item!
                </div>
                <h3 class="font-black text-gray-900 text-2xl uppercase italic tracking-tight">
                    Koleksi Terfavorit
                </h3>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5 md:gap-6 relative z-10">
                @foreach($products->shuffle()->take(5) as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </div>

    {{-- 4. SEMUA KOLEKSI --}}
    <div id="koleksi" class="container mx-auto px-4 mt-20">
        <div class="mb-12 border-b-2 border-gray-100 pb-5 flex items-center justify-between">
            <h3 class="text-3xl font-black text-gray-900 italic uppercase tracking-tighter flex items-center gap-3">
                <i data-lucide="box" class="w-6 h-6 text-[#E1700F]"></i>
                Semua Koleksi Murazon
            </h3>
            <div class="flex items-center gap-2 px-4 py-1.5 rounded-full bg-gray-100 border border-gray-200">
                <i data-lucide="layers-3" class="w-4 h-4 text-gray-400"></i>
                <span class="text-gray-500 text-sm font-bold">{{ $products->count() }} Produk</span>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-10">
            @forelse($products as $product)
                <a href="/products/{{ $product->id }}" 
                   class="product-card group bg-white rounded-[2rem] border border-gray-100 overflow-hidden flex flex-col relative shadow-sm">
                    
                    @if($product->is_cod_available)
                    <div class="absolute top-4 left-4 z-10 bg-black/80 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5 uppercase italic tracking-wider">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i> COD
                    </div>
                    @endif

                    <div class="aspect-square bg-gray-50 flex items-center justify-center p-6 relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-700 ease-out">
                        @else
                            <div class="text-gray-200">
                                <i data-lucide="cooking-pot" class="w-20 h-20 stroke-[1]"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 flex flex-col grow">
                        <span class="text-[10px] font-bold text-[#E1700F] uppercase tracking-widest mb-2.5">
                            {{ $product->category ?? 'Premium Cookware' }}
                        </span>
                        <h2 class="text-sm md:text-base font-semibold text-gray-900 line-clamp-2 h-10 md:h-12 mb-4 leading-snug group-hover:text-[#E1700F] transition-colors">
                            {{ $product->name }}
                        </h2>
                        <div class="mt-auto pt-3 border-t border-gray-100">
                            <p class="text-lg md:text-xl font-black text-gray-950 flex items-baseline gap-1">
                                <span class="text-xs text-[#E1700F] font-bold">Rp</span>
                                {{ number_format($product->price) }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-32 text-center bg-white rounded-3xl border border-gray-100 shadow-inner">
                    <div class="text-gray-200 mb-6 flex justify-center">
                        <i data-lucide="package-search" class="w-24 h-24 stroke-[1]"></i>
                    </div>
                    <p class="text-gray-400 font-bold uppercase italic tracking-widest text-lg">Koleksi sedang dipersiapkan...</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Tidak butuh Swiper JS --}}
@endpush