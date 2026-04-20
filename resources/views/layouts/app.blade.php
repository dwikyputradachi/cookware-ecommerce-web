<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Murazon Cookware')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        html { scroll-behavior: smooth; }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            -webkit-font-smoothing: antialiased; 
            overflow-x: hidden; /* Fix mutlak buat Android meluber */
            width: 100%;
            position: relative;
        }
        
        .nav-glass {
            background: rgba(107, 48, 5, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        #cart-badge {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#1E293B]">

@php
    $cartCount = collect(session('cart', []))->sum(fn($i) => $i['quantity']);   
@endphp

{{-- Navbar --}}
<nav class="nav-glass sticky top-0 z-50 border-b border-orange-900/30 shadow-lg">
    <div class="container mx-auto px-3 sm:px-6 py-2.5">
        <div class="flex items-center justify-between gap-1 sm:gap-4">
            
            {{-- Area Logo: Pakai min-w-0 supaya text nggak maksa lebar --}}
            <a href="/" class="flex items-center gap-2 sm:gap-3 shrink group transition-transform active:scale-95 min-w-0">
                <img src="{{ asset('img/logo-murazon.png') }}"
                     style="filter: brightness(0) saturate(100%) invert(1)" 
                     alt="Murazon Logo" 
                     class="h-8 md:h-14 w-auto drop-shadow-md shrink-0">
                
               <div class="flex flex-col leading-tight hidden xs:flex min-w-0">
                    <span class="text-base md:text-xl font-black tracking-tighter text-white italic truncate">MURAZON</span>
                    <span class="text-[7px] md:text-[10px] text-orange-200 font-bold uppercase tracking-widest leading-none truncate">Premium Cookware</span>
                </div>
            </a>
            
            {{-- Search Bar Tengah: Muncul di layar Lebar (LG) biar nggak desek-desekan di Tablet --}}
            <div class="hidden lg:flex flex-1 max-w-md mx-4">
                <form action="/" method="GET" class="w-full relative group">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Cari produk Murazon..." 
                           class="w-full bg-white/10 border border-white/20 rounded-2xl py-2 pl-11 pr-4 text-sm text-white placeholder:text-white/50 focus:bg-white focus:text-[#6B3005] focus:placeholder:text-gray-400 outline-none transition-all">
                    <div class="absolute left-4 top-2.5 text-white/50 group-focus-within:text-orange-500">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </div>
                </form>
            </div>
            
            {{-- Action Buttons: Pakai shrink-0 biar tombol gak gepeng --}}
            <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                
                {{-- Medsos --}}
                <div class="hidden sm:flex items-center gap-1 border-r border-white/10 pr-2">
                    <a href="#" class="p-1.5 text-orange-100 hover:text-white transition-colors">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="p-1.5 text-orange-100 hover:text-white transition-colors">
                        <i class="fa-brands fa-facebook text-sm"></i>
                    </a>
                </div>
                
                 {{-- Tombol Search Mobile --}}
                <button onclick="toggleMobileSearch()" class="lg:hidden p-2 bg-white/10 text-white rounded-xl border border-white/10 active:bg-orange-500 transition-all shrink-0">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>

                {{-- Cart --}}
                <a href="/cart" class="group relative flex items-center justify-center p-2 bg-white/10 hover:bg-orange-500 rounded-xl border border-white/10 transition-all active:scale-90 shrink-0">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                    <span id="cart-badge" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -top-1 -right-1 bg-white text-[#6B3005] text-[9px] font-black w-4 h-4 flex items-center justify-center rounded-full shadow-md border border-orange-500">
                        {{ $cartCount }}
                    </span>
                </a>
            </div>
        </div>

        {{-- Mobile Search Dropdown --}}
        <div id="mobile-search" class="hidden lg:hidden mt-3 pb-2 transition-all">
            <form action="/" method="GET" class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari produk Murazon..." 
                       class="w-full bg-white border border-gray-200 rounded-xl py-2.5 pl-11 pr-4 text-sm outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 shadow-sm">
                <div class="absolute left-4 top-3 text-gray-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
            </form>
        </div>
    </div>
</nav>

<main class="min-h-screen">
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-100 py-10 mt-10">
    <div class="container mx-auto px-4 text-center">
        <img src="{{ asset('img/logo-murazon.png') }}" alt="Logo" class="h-6 mx-auto mb-4 grayscale opacity-40">
        <div class="flex justify-center gap-6 mb-4 text-gray-400">
            <a href="#" class="hover:text-orange-600 transition-colors"><i class="fa-brands fa-instagram text-lg"></i></a>
            <a href="#" class="hover:text-blue-600 transition-colors"><i class="fa-brands fa-facebook text-lg"></i></a>
        </div>
        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest italic">
            &copy; 2026 Murazon • Premium Cookware
        </p>
    </div>
</footer>

{{-- Floating WhatsApp --}}
<div class="fixed bottom-6 right-6 z-50">
    <a href="https://wa.me/6282285455631?text=Halo%20Murazon,%20saya%20ingin%20tanya..." 
       target="_blank"
       class="flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-tr from-green-600 to-green-400 shadow-xl transition-all hover:scale-110 active:scale-95">
        <i class="fab fa-whatsapp text-2xl sm:text-4xl text-white"></i>
    </a>
</div>

@stack('scripts')
<script>
    lucide.createIcons();
    window.refreshIcons = () => { lucide.createIcons(); };
    function toggleMobileSearch() {
        const searchBar = document.getElementById('mobile-search');
        searchBar.classList.toggle('hidden');
        if(!searchBar.classList.contains('hidden')) {
            searchBar.querySelector('input').focus();
        }
    }
</script>
</body>
</html>
