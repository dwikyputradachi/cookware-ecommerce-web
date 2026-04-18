<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Santo Cookware')</title>
    
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
            -moz-osx-font-smoothing: grayscale;
        }
        
        .nav-glass {
            background: rgba(37, 99, 235, 0.9);
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
            <a href="/" class="text-sm font-semibold text-blue-50 hover:text-white transition-colors">Home</a>
            <a href="#" class="text-sm font-semibold text-blue-50 hover:text-white transition-colors">Facebook</a>
            <a href="#" class="text-sm font-semibold text-blue-50 hover:text-white transition-colors">Instagram</a>
            <a href="#" class="text-sm font-semibold text-blue-50 hover:text-white transition-colors">Youtube</a>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="/cart" class="group relative flex items-center justify-center p-2.5 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all active:scale-90">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
                <span id="cart-badge" class="{{ $cartCount > 0 ? '' : 'hidden' }} absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold min-w-5 h-5 flex items-center justify-center px-1 rounded-full border-2 border-blue-600 shadow-lg">
                    {{ $cartCount }}
                </span>
            </a>
        </div>
    </div>
</nav>

<main class="min-h-screen">
    @yield('content')
</main>

{{-- Footer --}}
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

{{-- Floating WhatsApp --}}
<div class="fixed bottom-6 right-6 z-50 group">
    <div class="absolute bottom-full right-0 mb-5 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0 pointer-events-none">
        <div class="bg-white/95 backdrop-blur-sm px-4 py-2.5 rounded-2xl shadow-2xl border border-gray-100 flex items-center gap-3">
            <div class="relative">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-ping"></div>
                <div class="absolute inset-0 w-2 h-2 bg-green-500 rounded-full"></div>
            </div>
            <p class="text-sm font-bold text-slate-700 whitespace-nowrap">Ada pertanyaan? Chat Pak Santo 👋</p>
        </div>
        <div class="w-3 h-3 bg-white border-r border-b border-gray-100 rotate-45 absolute -bottom-1.5 right-6 shadow-sm"></div>
    </div>
    
    <a href="https://wa.me/6282285455631?text=Halo%20Santo%20Cookware,%20saya%20ingin%20tanya..." 
       target="_blank"
       class="relative flex items-center justify-center w-16 h-16 rounded-3xl shadow-[0_20px_50px_rgba(34,197,94,0.3)] transition-all duration-500 hover:scale-110 active:scale-95 overflow-hidden">
        
        <div class="absolute inset-0 bg-gradient-to-tr from-green-600 to-green-400 group-hover:from-green-500 group-hover:to-emerald-400 transition-all"></div>
        <div class="absolute inset-0 rounded-full bg-white opacity-20 animate-ping group-hover:animate-none"></div>

        <i class="fab fa-whatsapp relative text-4xl text-white group-hover:rotate-12 transition-transform duration-300"></i>
    </a>
</div>

@stack('scripts')

<script>
    // Initialize Icons
    lucide.createIcons();

    window.refreshIcons = () => {
        lucide.createIcons();
    };
</script>

</body>
</html>