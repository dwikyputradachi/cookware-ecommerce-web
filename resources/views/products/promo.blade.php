@extends('layouts.app')

@section('title', 'Promo Spesial - Murazon Shopping Market')

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

    {{-- 1. HERO BANNER --}}
<div class="container mx-auto px-4 pt-8">
    @if(isset($banners) && $banners->isNotEmpty())
        <div class="relative rounded-4xl overflow-hidden shadow-2xl"
            x-data="carousel({{ $banners->count() }})"
            x-init="init()"
            style="height: 420px;"
            @touchstart.passive="touchStart($event)"
            @touchend.passive="touchEnd($event)">

            <div class="relative w-full h-full">
                @foreach($banners as $i => $banner)
                    <div class="absolute inset-0 transition-opacity duration-700"
                        :class="current === {{ $i }} ? 'opacity-100 z-10' : 'opacity-0 z-0'">

                        @if($banner->link)
                            <a href="{{ $banner->link }}">
                        @endif

                            <img src="{{ $banner->image }}"
                                alt="{{ $banner->title }}"
                                class="w-full h-full object-cover">

                            @if($banner->title)
                                <div class="absolute bottom-0 left-0 right-0 p-6 bg-linear-to-t from-black/50 to-transparent">
                                    <p class="text-white font-bold text-lg md:text-2xl drop-shadow">
                                        {{ $banner->title }}
                                    </p>
                                </div>
                            @endif

                        @if($banner->link)
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>

            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>

            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                @foreach($banners as $i => $banner)
                    <button @click="goTo({{ $i }})"
                        :class="current === {{ $i }} ? 'bg-white w-6' : 'bg-white/50 w-2'"
                        class="h-2 rounded-full transition-all duration-300">
                    </button>
                @endforeach
            </div>
        </div>
    @else
        <div class="rounded-[3rem] p-10 md:p-20 text-white shadow-2xl border-4 border-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #4c2203 0%, #a1500a 40%, #E1700F 100%);">
            <div class="relative z-10 text-center">
                <h2 class="text-4xl md:text-6xl font-black italic uppercase tracking-tighter text-white mb-3">
                    Promo <span style="color: #FDBA74;">Murazon</span>
                </h2>
                <p class="text-orange-100 text-sm md:text-lg opacity-90">
                    Produk pilihan dengan penawaran spesial
                </p>
            </div>
        </div>
    @endif
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
@push('scripts')
<script>
function carousel(total) {
    return {
        current: 0,
        total: total,
        timer: null,
        startX: 0,

        init() {
            this.startAuto();
        },

        startAuto() {
            this.timer = setInterval(() => this.next(), 4000);
        },

        resetAuto() {
            clearInterval(this.timer);
            this.startAuto();
        },

        next() {
            this.current = (this.current + 1) % this.total;
        },

        prev() {
            this.current = (this.current - 1 + this.total) % this.total;
            this.resetAuto();
        },

        goTo(i) {
            this.current = i;
            this.resetAuto();
        },

        touchStart(e) {
            this.startX = e.touches[0].clientX;
        },

        touchEnd(e) {
            const diff = this.startX - e.changedTouches[0].clientX;

            if (Math.abs(diff) > 40) {
                diff > 0 ? this.next() : this.prev();
                this.resetAuto();
            }
        }
    }
}
</script>
@endpush