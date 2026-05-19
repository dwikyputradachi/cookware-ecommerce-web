<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Murazon Cookware')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; width: 100%; }
        .nav-gradient { background: linear-gradient(to right, #4c2203, #6B3005); }
        .footer-social-icon { transition: all 0.3s ease; }
        .footer-social-icon:hover { transform: translateY(-3px); }
        /* Perbaikan Dropdown Z-Index */
        .dropdown-content { z-index: 100 !important; }
    </style>
</head>
<body class="bg-[#F8FAFC]" x-data="{ mobileMenu: false }">

@php
    $cartCount = collect(session('cart', []))->sum(fn($i) => $i['quantity'] ?? 0);   
@endphp

<nav class="nav-gradient sticky top-0 z-100 shadow-xl">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between gap-4">
            
            <a href="/" class="inline-flex flex-col items-start shrink-0 group w-fit">
                <img 
                    src="{{ asset('img/logo-murazon.png') }}" 
                    alt="Murazon"
                    class="h-6 md:h-7 w-auto object-contain mb-0.5 transition-transform group-hover:scale-105">
                <span class="block w-full text-[9px] md:text-[12px] font-regular text-white leading-none tracking-[0.06wem] text-center">
                    Murazon Shopping Market
                </span>
            </a>

            <div class="hidden lg:flex flex-1 max-w-xl items-center bg-white/10 rounded-xl border border-white/20 overflow-visible relative">
                <div class="relative group border-r border-white/20">
                    <button class="flex items-center gap-2 px-5 py-2.5 text-white text-xs font-bold hover:bg-white/10 transition-all">
                        <i data-lucide="menu" class="w-4 h-4 text-orange-300"></i>
                        Kategori
                        <i data-lucide="chevron-down" class="w-3 h-3 group-hover:rotate-180 transition-transform"></i>
                    </button>
                    <div class="absolute top-full left-0 w-60 bg-white shadow-2xl rounded-b-xl py-3 hidden group-hover:block border border-gray-100 dropdown-content animate-in fade-in slide-in-from-top-2 duration-200">
                        @foreach($categories as $cat)
                            <a href="{{ ($cat['name'] == 'Semua') ? '/' : '/?category=' . $cat['name'] }}" class="block px-5 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 font-semibold transition-colors">
                                {{ $cat['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <form action="/" method="GET" class="flex-1 flex items-center relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perlengkapan dapur..." class="w-full bg-transparent px-4 py-2 text-sm text-white placeholder:text-white/40 outline-none">
                    <button type="submit" class="pr-4 text-white/50 hover:text-white transition-colors">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden sm:flex items-center gap-3">
                    <a href="/promo" class="flex items-center gap-2 bg-orange-500/20 hover:bg-orange-500/40 px-3 py-1.5 rounded-lg border border-orange-400/30 transition-all text-[10px] font-bold text-orange-200 uppercase tracking-tight">
                        <i data-lucide="zap" class="w-3.5 h-3.5 fill-orange-400 text-orange-400"></i>
                        Promo
                    </a>
                    <a href="/cart" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg border border-white/10 transition-all text-[10px] font-bold text-white uppercase tracking-tight">
                        <i data-lucide="shopping-cart" class="w-4 h-4 text-orange-300"></i> 
                        Keranjang ({{ $cartCount }})
                    </a>
                </div>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg border border-white/10 transition-all text-[10px] font-bold text-white uppercase tracking-tight">
                    <i data-lucide="package" class="w-4 h-4 text-orange-300"></i>
                    Pesanan
                </a>

                <a href="/cart" class="relative p-2 bg-orange-500 rounded-lg text-white lg:hidden">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="absolute -top-1 -right-1 bg-white text-orange-700 text-[8px] font-black w-4 h-4 flex items-center justify-center rounded-full border border-orange-600">{{ $cartCount }}</span>
                </a>

                <button @click="mobileMenu = !mobileMenu" class="p-2 text-white bg-white/10 rounded-lg lg:hidden focus:outline-none">
                    <i data-lucide="menu" x-show="!mobileMenu"></i>
                    <i data-lucide="x" x-show="mobileMenu" x-cloak></i>
                </button>
            </div>
        </div>

        <div class="mt-3 lg:hidden">
            <form action="/" method="GET" class="flex items-center bg-white/10 rounded-xl border border-white/20 overflow-hidden px-3">
                <i data-lucide="search" class="w-4 h-4 text-white/40"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di Murazon..." class="w-full bg-transparent px-3 py-2.5 text-sm text-white placeholder:text-white/40 outline-none">
            </form>
        </div>
    </div>

    <div x-show="mobileMenu" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak class="lg:hidden bg-white border-t border-gray-100 shadow-2xl">
        <div class="p-4 space-y-4">
            <a href="#" class="flex items-center justify-between p-4 bg-orange-50 rounded-xl text-xs font-bold text-orange-700">
                <span class="flex items-center gap-2"><i data-lucide="zap" class="w-4 h-4"></i> Promo Hari Ini</span>
                <span class="bg-orange-600 text-white text-[8px] px-2 py-0.5 rounded-full">HOT</span>
            </a>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Kategori Produk</p>
            <div class="grid grid-cols-2 gap-2">
                @foreach($categories as $cat)
                    <a href="{{ ($cat['name'] == 'Semua') ? '/' : '/?category=' . $cat['name'] }}" class="p-3 bg-gray-50 rounded-xl text-xs font-semibold text-gray-700 border border-transparent hover:border-orange-200 hover:bg-orange-50 transition-all">
                        {{ $cat['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</nav>

<main class="min-h-screen">@yield('content')</main>

<footer class="bg-white border-t border-gray-100 pt-16 pb-8 mt-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div>
                <img src="{{ asset('img/logo-murazon.png') }}" class="h-10 mb-4">
                <p class="text-[16px] font-medium text-orange-600 tittlecase mb-4 tracking-tighter">Murazon Shopping Market</p>
                <div class="space-y-2 text-sm text-gray-500 font-medium">
                    <p>Jam Operasional : 09.00 wib - 18.00 wib</p>
                    <p>Whatsapp : +62 812-703-0826</p>
                    <p>E-mail : customer_service@murazon.com</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm font-semibold text-gray-600">
                <div class="space-y-3">
                    <a href="{{ route('about.us') }}" class="block hover:text-orange-600 transition-colors">Tentang Kami</a>
                    <a href="{{ route('garansi') }}" class="block hover:text-orange-600 transition-colors">Kebijakan Garansi</a>
                    <a href="{{ route('return') }}" class="block hover:text-orange-600 transition-colors">Ketentuan return barang dan penggantian uang</a>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('panduan') }}" class="block hover:text-orange-600 transition-colors">Panduan Belanja</a>
                    <a href="{{ route('penipuan') }}" class="block hover:text-orange-600 transition-colors">Waspada Penipuan</a>
                   
                </div>
            </div>
            <div class="flex flex-col items-start md:items-end gap-6">
                <div class="flex gap-3">
                    <a href="#" class="footer-social-icon w-10 h-10 bg-[#3b5998] text-white rounded-lg flex items-center justify-center shadow-md"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="footer-social-icon w-10 h-10 bg-linear-to-tr from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] text-white rounded-lg flex items-center justify-center shadow-md"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="footer-social-icon w-10 h-10 bg-[#25D366] text-white rounded-lg flex items-center justify-center shadow-md"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="footer-social-icon w-10 h-10 bg-black text-white rounded-lg flex items-center justify-center shadow-md"><i class="fab fa-tiktok"></i></a>
                </div>
                <div class="text-sm text-gray-400 font-bold tracking-[0.2em] uppercase">Ikuti Kami</div>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-8 text-center">
            <p class="text-[10px] text-gray-400 font-bold tracking-[0.2em] uppercase">&copy; 2026 MURAZON • Corporation</p>
        </div>
    </div>
</footer>

<div class="fixed bottom-6 right-6 z-110">
    <a href="https://wa.me/628127030826" target="_blank" class="flex items-center justify-center w-14 h-14 rounded-2xl bg-[#25D366] shadow-2xl transition-all hover:scale-110 text-white">
        <i class="fab fa-whatsapp text-3xl"></i>
    </a>
</div>

@stack('scripts')
<script>
    function initLucide() {
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }
    document.addEventListener('DOMContentLoaded', initLucide);
    document.addEventListener('alpine:initialized', initLucide);
</script>
</body>
</html>
