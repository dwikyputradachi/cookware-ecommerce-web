@extends('layouts.app')

@section('title', $product->name . ' - Santo Cookware')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">

    {{-- Logika Deteksi Video YouTube --}}
    @php
        $videoId = null;
        if ($product->video_url) {
            $pattern = '%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|shorts/))([\w-]{11})%i';
            if (preg_match($pattern, $product->video_url, $match)) {
                $videoId = $match[1];
            }
        }
    @endphp

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="/" class="hover:text-blue-600 transition-colors">Katalog</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium truncate">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm flex flex-col md:flex-row">
        
        {{-- Media Section --}}
        <div class="w-full md:w-1/2 relative bg-gray-50 p-4 md:p-6" 
             x-data="{ 
                slide: 1, 
                total: {{ $videoId ? 2 : 1 }},
                autoplay: null,
                init() {
                    if(this.total > 1) { this.startAutoplay(); }
                },
                startAutoplay() {
                    this.autoplay = setInterval(() => {
                        this.slide = this.slide === this.total ? 1 : this.slide + 1;
                    }, 6000); 
                },
                stopAutoplay() {
                    if(this.autoplay) clearInterval(this.autoplay);
                }
             }"
             @mouseenter="stopAutoplay()" 
             @mouseleave="startAutoplay()">
            
            <div class="relative group overflow-hidden rounded-4xl shadow-inner border-4 border-white bg-white">
                
                <div class="relative h-87.5 md:h-110">
                    {{-- Slide 1: Selalu Muncul (Foto atau Placeholder) --}}
                    <div x-show="slide === 1" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0 w-full h-full">
                        
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            {{-- Container Placeholder agar Slide 1 TIDAK KOSONG --}}
                            <div class="w-full h-full bg-blue-50 flex flex-col items-center justify-center text-blue-600 p-6">
                                <i data-lucide="image" class="w-16 h-16 mb-4 opacity-20"></i>
                                <span class="font-bold tracking-widest text-sm uppercase">Santo Cookware</span>
                                <p class="text-[10px] mt-2 opacity-50 uppercase tracking-tighter">Gambar sedang disiapkan</p>
                            </div>
                        @endif
                    </div>

                    {{-- Slide 2: Video --}}
                    @if($videoId)
                    <div x-show="slide === 2" x-cloak
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0 bg-black">
                        <iframe class="w-full h-full" 
                                src="https://www.youtube.com/embed/{{ $videoId }}" 
                                frameborder="0" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    @endif
                </div>

                {{-- Tombol Navigasi Manual --}}
                @if($videoId)
                <button @click="slide = (slide === 1 ? 2 : 1)" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 text-blue-600 p-3 rounded-2xl shadow-xl z-30 transition-all active:scale-90 border border-gray-100 opacity-100 md:opacity-0 md:group-hover:opacity-100">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>

                <button @click="slide = (slide === 1 ? 2 : 1)" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 text-blue-600 p-3 rounded-2xl shadow-xl z-30 transition-all active:scale-90 border border-gray-100 opacity-100 md:opacity-0 md:group-hover:opacity-100">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                {{-- Indikator Slide --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-30">
                    <div class="h-1.5 transition-all duration-300 rounded-full" :class="slide === 1 ? 'w-8 bg-blue-600' : 'w-2 bg-gray-300'"></div>
                    <div class="h-1.5 transition-all duration-300 rounded-full" :class="slide === 2 ? 'w-8 bg-blue-600' : 'w-2 bg-gray-300'"></div>
                </div>
                @endif
            </div>

            {{-- Label COD --}}
            @if($product->is_cod_available)
                <div class="absolute top-10 left-10 bg-green-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1 z-40">
                    <i data-lucide="truck" class="w-3 h-3"></i> BISA COD
                </div>
            @endif
        </div>

        {{-- Detail Section --}}
        <div class="p-8 md:p-12 flex flex-col flex-1">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
                
                <div class="flex items-center gap-4 mt-4">
                    <p class="text-3xl font-extrabold text-blue-600">Rp {{ number_format($product->price) }}</p>
                    <div class="h-6 w-px bg-gray-200"></div>
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
                        <span class="text-xs font-medium text-gray-600">Original Bos</span>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-3">Deskripsi Produk</h3>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>
            </div>

            {{-- Action Buttons --}}
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
            @endif
        </div>
    </div>
</div>
@endsection