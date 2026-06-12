@extends('layouts.app')

@section('title', $product->name . ' - Murazon Cookware')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">

    @php
        $videoId = null;
        if ($product->video_url) {
            $pattern = '%^(?:https?://)?(?:www\.)?(?:youtu\.be/|youtube\.com/(?:embed/|v/|watch\?v=|shorts/))([\w-]{11})%i';
            if (preg_match($pattern, $product->video_url, $match)) {
                $videoId = $match[1];
            }
        }
        $totalSlides = $videoId ? 2 : 1;
    @endphp

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="/" class="hover:text-(--color-secondary) transition-colors">Catalog</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800 font-medium truncate">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-[2.5rem] border border-orange-100 overflow-hidden shadow-sm flex flex-col md:flex-row">
        
        {{-- Media Section --}}
        <div class="w-full md:w-1/2 relative bg-orange-50 p-4 md:p-6" 
             x-data="{ 
                slide: 1, 
                total: {{ $totalSlides }},
                next() { this.slide = this.slide === this.total ? 1 : this.slide + 1 },
                prev() { this.slide = this.slide === 1 ? this.total : this.slide - 1 }
             }">
            
            <div class="relative group overflow-hidden rounded-4xl shadow-inner border-4 border-white bg-white">
                
                <div class="relative h-80 md:h-110">
                    
                    {{-- SLIDE 1: IMAGE --}}
                    <div x-show="slide === 1" 
                         x-cloak
                         class="absolute inset-0 w-full h-full bg-white"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        @if($product->image)
                            <img src="{{ str_contains($product->image ?? '', 'http') ? $product->image : asset('storage/' . $product->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-orange-50 flex flex-col items-center justify-center text-(--color-secondary) p-6">
                                <i data-lucide="image" class="w-16 h-16 mb-4 opacity-20"></i>
                                <span class="font-bold tracking-widest text-sm uppercase">Murazon</span>
                                <p class="text-[10px] mt-2 opacity-50 uppercase tracking-tighter">Gambar sedang disiapkan</p>
                            </div>
                        @endif
                    </div>

                    {{-- SLIDE 2: VIDEO --}}
                    @if($videoId)
                    <div x-show="slide === 2" 
                         x-cloak
                         class="absolute inset-0 bg-black z-20"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100">
                        <iframe class="w-full h-full" 
                                src="https://www.youtube.com/embed/{{ $videoId }}?enablejsapi=1&autoplay=0" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                        </iframe>
                    </div>
                    @endif
                </div>

                {{-- NAV BUTTONS --}}
                @if($videoId)
                <button @click="prev()" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 text-(--color-secondary) p-3 rounded-2xl shadow-xl z-30 border border-orange-100 hover:bg-white active:scale-90 transition-all">
                    <i data-lucide="chevron-left" class="w-6 h-6"></i>
                </button>

                <button @click="next()" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 text-(--color-secondary) p-3 rounded-2xl shadow-xl z-30 border border-orange-100 hover:bg-white active:scale-90 transition-all">
                    <i data-lucide="chevron-right" class="w-6 h-6"></i>
                </button>

                {{-- INDICATOR --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-30">
                    <button @click="slide = 1" class="h-1.5 transition-all rounded-full" :class="slide === 1 ? 'w-8 bg-(--color-secondary)' : 'w-2 bg-gray-300'"></button>
                    <button @click="slide = 2" class="h-1.5 transition-all rounded-full" :class="slide === 2 ? 'w-8 bg-(--color-secondary)' : 'w-2 bg-gray-300'"></button>
                </div>
                @endif
            </div>

            {{-- LABEL COD --}}
            @if($product->is_cod_available)
                <div class="absolute top-10 left-10 bg-(--color-secondary) text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1 z-40">
                    <i data-lucide="truck" class="w-3 h-3"></i> BISA COD
                </div>
            @endif
        </div>

        {{-- DETAIL SECTION --}}
        <div class="p-8 md:p-12 flex flex-col flex-1">
    <div class="flex-1">
        {{-- Label Promo (Floating di atas nama barang) --}}
        @if($product->is_promo && $product->discount_price)
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-red-600 text-[10px] font-bold uppercase tracking-wider mb-3">
                <i data-lucide="tag" class="w-3 h-3"></i> Penawaran Promo
            </div>
        @endif

        <h1 class="text-3xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h1>
        
        <div class="flex flex-col mt-4">
            {{-- Logic Harga Promo vs Normal --}}
            @if($product->is_promo && $product->discount_price)
                <div class="flex flex-col">
                    <div class="flex items-center gap-3">
                        <p class="text-4xl font-black text-red-600 tracking-tighter">
                            Rp {{ number_format($product->discount_price) }}
                        </p>
                        <span class="px-2 py-1 bg-red-600 text-white text-[10px] font-bold rounded-lg shadow-sm">
                            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                        </span>
                    </div>
                    <p class="text-sm text-gray-400 font-medium line-through mt-1">
                        Rp {{ number_format($product->price) }}
                    </p>
                </div>
            @else
                <p class="text-4xl font-black text-(--color-secondary) tracking-tighter">
                    Rp {{ number_format($product->price) }}
                </p>
            @endif

            <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-50">
                <p class="text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }} font-bold uppercase tracking-widest flex items-center gap-1.5">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $product->stock > 0 ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 {{ $product->stock > 0 ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    </span>
                    {{ $product->stock > 0 ? 'Stok Tersedia (' . $product->stock . ')' : 'Stok Habis' }}
                </p>
                <div class="h-4 w-px bg-gray-200"></div>
                <p class="text-sm text-gray-500 font-medium italic">Terjual {{ $product->total_sold }}+</p>
            </div>
        </div>
                <div class="grid grid-cols-2 gap-4 mt-8 py-6 border-y border-orange-100">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-orange-50 rounded-lg text-(--color-secondary)">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-600">Kualitas Premium</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-orange-50 rounded-lg text-(--color-secondary)">
                            <i data-lucide="award" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-600">Original</span>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-3">Deskripsi Produk</h3>
                    <p class="text-gray-500 text-base leading-relaxed">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>
            </div>
            {{-- SECTION REVIEW --}}
            <div class="mt-12 border-t border-orange-100 pt-10">
                <h3 class="text-lg font-bold text-gray-900 uppercase tracking-tight mb-6 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5 text-[#E1700F]"></i>
                    Ulasan Pembeli
                </h3>

                {{-- Form Review --}}
                {{-- Ganti $order_id dengan cara kamu passing order_id ke halaman ini --}}
                @if(isset($order_id))
                <form action="{{ route('reviews.store') }}" method="POST" class="bg-orange-50 rounded-2xl p-6 mb-8 border border-orange-100">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="order_id" value="{{ $order_id }}">

                    <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-4">Tulis Ulasanmu</p>

                    {{-- Nama --}}
                    <input type="text" name="customer_name" placeholder="Nama kamu" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-100 text-sm mb-3 focus:ring-1 focus:ring-[#E1700F] outline-none bg-white">

                    {{-- Bintang --}}
                    <div class="flex items-center gap-1 mb-3" id="star-container">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})"
                            class="star-btn text-2xl text-gray-200 hover:text-orange-400 transition" data-value="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="">

                    {{-- Komentar --}}
                    <textarea name="comment" placeholder="Ceritakan pengalamanmu dengan produk ini (min. 5 karakter)..." rows="3" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-100 text-sm mb-4 focus:ring-1 focus:ring-[#E1700F] outline-none bg-white"></textarea>

                    @if(session('error'))
                        <p class="text-xs text-red-500 font-bold mb-3">{{ session('error') }}</p>
                    @endif

                    <button type="submit"
                        class="w-full bg-[#E1700F] text-white py-3 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-black transition">
                        Kirim Ulasan
                    </button>
                </form>
                @endif

                {{-- List Review --}}
                <div class="space-y-4">
                    @forelse($product->reviews as $review)                    <div class="bg-white rounded-2xl border border-gray-100 p-5">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-xs font-black text-[#E1700F]">
                                    {{ strtoupper(substr($review->customer_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-800">{{ $review->customer_name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex text-orange-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $review->comment }}</p>
                    </div>
                    @empty
                    <div class="text-center py-10 border-2 border-dashed border-gray-100 rounded-2xl">
                        <i data-lucide="message-circle" class="w-8 h-8 text-gray-200 mx-auto mb-2"></i>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Belum ada ulasan</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @push('scripts')
            <script>
            function setRating(val) {
                document.getElementById('rating-input').value = val;
                document.querySelectorAll('.star-btn').forEach(btn => {
                    btn.style.color = btn.dataset.value <= val ? '#f97316' : '#e5e7eb';
                });
            }
            </script>
            @endpush
             {{-- Info Pengiriman (Mirip Erablue) --}}
                    <div class="mt-6 p-4 bg-blue-50 rounded-2xl border border-blue-100">
                        <p class="text-[11px] text-blue-800 font-bold flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4"></i>
                            KONFIRMASI ALAMAT PENGIRIMAN UNTUK BIAYA DAN ESTIMASI WAKTU
                        </p>
                    </div>
            {{-- ACTION BUTTONS --}}
            @if($product->stock > 0)
            <div class="mt-10 flex flex-col sm:flex-row gap-4">
                
                <form action="/cart/add/{{ $product->id }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="redirect" value="back">
                    <button class="w-full flex items-center justify-center gap-2 border-2 border-[#E1700F] text-[#E1700F] hover:bg-orange-50 py-4 rounded-2xl font-bold transition-all">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        + Keranjang
                    </button>
                </form>

                <form action="/cart/add/{{ $product->id }}" method="POST" class="flex-[1.5]">
                    @csrf
                    <input type="hidden" name="redirect" value="cart">
                    <button class="w-full flex items-center justify-center gap-2 bg-[#E1700F] hover:brightness-110 text-white py-4 rounded-2xl font-bold shadow-[0_10px_30px_rgba(225,112,15,0.3)]">
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