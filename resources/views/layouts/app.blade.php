<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Santo Cookware')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Smooth scrolling ala mobile app */
        html { scroll-behavior: smooth; }
        
        /* Font smoothing agar teks terlihat tajam & tipis */
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            -webkit-font-smoothing: antialiased; 
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Glassmorphism Effect untuk Navbar */
        .nav-glass {
            background: rgba(37, 99, 235, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Badge Animation */
        #cart-badge {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#1E293B]">

@php
    // Menghitung total quantity dari semua item di session
    $cartCount = collect(session('cart', []))->sum(fn($i) => $i['quantity']);   
@endphp

<nav class="nav-glass sticky top-0 z-50 border-b border-blue-400/20 shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
        
        <a href="/" class="flex items-center gap-2 group transition-transform active:scale-95">
            <div class="bg-white p-1.5 rounded-xl shadow-sm group-hover:shadow-md transition-all">
                <i data-lucide="cooking-pot" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="flex flex-col leading-tight">
                <span class="text-lg font-bold tracking-tight text-white uppercase italic">
                    Santo<span class="font-light opacity-80 uppercase">Cook</span>
                </span>
                <span class="text-[10px] text-blue-100 font-medium tracking-widest uppercase">Premium Tools</span>
            </div>
        </a>
        
        <div class="hidden md:flex items-center gap-8">
            <a href="/" class="text-sm font-semibold text-blue-50 hover:text-white transition-colors">
                Katalog Produk
            </a>
            <a href="https://facebook.com" class="text-sm font-semibold text-blue-50 hover:text-white transition-colors">
                Facebook
            </a>
            <a href="https://instagram.com" class="text-sm font-semibold text-blue-50 hover:text-white transition-colors">
                Instagram
            </a>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="/cart" class="group relative flex items-center justify-center p-2.5 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all active:scale-90">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                
                <span id="cart-badge" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold min-w-5 h-5 flex items-center justify-center px-1 rounded-full border-2 border-santo-blue shadow-lg">
                    {{ $cartCount }}
                </span>
            </a>
        </div>
    </div>
</nav>

<main class="min-h-screen">
    @yield('content')
</main>

<footer class="bg-white border-t border-gray-100 py-10 mt-20">
    <div class="container mx-auto px-4 text-center">
        <div class="flex justify-center gap-6 mb-6 text-gray-400">
            <a href="#" class="hover:text-blue-600 transition-colors"><i data-lucide="facebook" class="w-5 h-5"></i></a>
            <a href="#" class="hover:text-pink-600 transition-colors"><i data-lucide="instagram" class="w-5 h-5"></i></a>
            <a href="#" class="hover:text-red-600 transition-colors"><i data-lucide="youtube" class="w-5 h-5"></i></a>
        </div>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-widest">
            &copy; 2026 Santo Cookware • High Quality Kitchenware
        </p>
    </div>
</footer>

<a href="https://wa.me/6282285455631" class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-2xl hover:bg-green-600 transition-all hover:-translate-y-1 active:scale-90 z-40">
    <i data-lucide="message-circle" class="w-6 h-6"></i>
</a>

@stack('scripts')

<script>
    // Initialize Lucide Icons pertama kali halaman di-load
    lucide.createIcons();

    /**
     * Fungsi Global untuk refresh icon setelah AJAX
     * Panggil window.refreshIcons() di dalam script cart.blade.php kamu
     */
    window.refreshIcons = () => {
        lucide.createIcons();
        console.log("Icons Refreshed!");
    };
</script>

</body>
</html>