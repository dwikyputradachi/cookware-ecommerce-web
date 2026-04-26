@extends('layouts.app')

@section('title', 'Katalog - Murazon Cookware')

@push('styles')
<style>
    :root {
        --primary: #E1700F;
        --primary-dark: #6B3005;
        --accent: #FDBA74;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .product-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, box-shadow;
    }
    .product-card:hover {
        transform: translateY(-8px) scale(1.01);
        box-shadow: 0 25px 50px -12px rgba(107, 48, 5, 0.15);
        border-color: rgba(225, 112, 15, 0.2);
    }
    #product-grid-wrapper {
        transition: opacity 0.2s ease;
    }
    #product-grid-wrapper.loading {
        opacity: 0.4;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen pb-20">

    {{-- 1. HERO --}}
    <div class="container mx-auto px-4 pt-8">
        <div class="rounded-[3rem] p-10 md:p-20 text-white shadow-2xl border-4 border-white relative overflow-hidden" 
             style="background: linear-gradient(135deg, #4c2203 0%, #a1500a 40%, #E1700F 100%);">
            <div class="absolute w-[400px] h-[400px] rounded-full blur-[100px] opacity-20 -top-20 -right-20 pointer-events-none" 
                 style="background: radial-gradient(circle, #FDBA74 0%, transparent 70%);"></div>
            <div class="relative z-10 grid md:grid-cols-2 gap-10 items-center">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/20 backdrop-blur-md border border-white/10 shadow-inner">
                        <i data-lucide="crown" class="w-4 h-4 text-orange-300"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-orange-100">The Culinary Standard</span>
                    </div>
                    <h2 class="text-4xl md:text-7xl font-black italic uppercase leading-[0.9] tracking-tighter text-white">
                        Authentic<br><span style="color: #FDBA74;">Cookware</span>
                    </h2>
                    <div class="p-5 rounded-2xl bg-black/20 backdrop-blur-sm border border-white/5 shadow-lg max-w-xl">
                        <p class="text-orange-50 text-sm md:text-lg font-medium opacity-90 leading-relaxed">
                            Mewujudkan kelezatan bintang lima di dapur Anda. Didesain dengan presisi, material premium, dan durabilitas tanpa kompromi untuk performa masak terbaik.
                        </p>
                    </div>
                    <div class="pt-4 flex flex-wrap gap-4 items-center">
                        <a href="#koleksi" class="px-8 py-3.5 bg-white text-gray-950 rounded-full font-extrabold text-sm uppercase tracking-wider shadow-lg hover:bg-orange-50 transition-all flex items-center gap-2 group">
                            Jelajahi Produk
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                        <span class="text-xs text-orange-200 font-bold tracking-wide uppercase">Garansi Seumur Hidup*</span>
                    </div>
                </div>
                <div class="relative flex justify-center md:justify-end">
                    <div class="relative z-10 transform md:translate-x-10 md:rotate-6">
                        <i data-lucide="cooking-pot" class="w-48 h-48 md:w-80 md:h-80 text-orange-300/30 stroke-[1.5]"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. FILTER BAR --}}
    <div id="filter-section" class="container mx-auto px-4 mt-6" x-data="filterBar()">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <div class="flex flex-wrap gap-3 items-center">

                {{-- Sort --}}
                <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-xl border border-gray-100">
                    <i data-lucide="arrow-up-down" class="w-3.5 h-3.5 text-gray-400"></i>
                    <select id="f-sort" onchange="applyFilter()" class="text-[11px] font-bold text-gray-600 bg-transparent outline-none cursor-pointer">
                        <option value="latest"     {{ request('sort') == 'latest'     ? 'selected' : '' }}>Terbaru</option>
                        <option value="popular"    {{ request('sort') == 'popular'    ? 'selected' : '' }}>Terlaris</option>
                        <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rating"     {{ request('sort') == 'rating'     ? 'selected' : '' }}>Rating Terbaik</option>
                    </select>
                </div>

                {{-- Kategori --}}
                <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-xl border border-gray-100">
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-gray-400"></i>
                    <select id="f-category" onchange="applyFilter()" class="text-[11px] font-bold text-gray-600 bg-transparent outline-none cursor-pointer">
                        <option value="semua">Semua Kategori</option>
                        @foreach($categories as $cat)
                            @if($cat['name'] !== 'Semua')
                            <option value="{{ $cat['name'] }}" {{ request('category') == $cat['name'] ? 'selected' : '' }}>{{ $cat['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- Rating --}}
                <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-xl border border-gray-100">
                    <i data-lucide="star" class="w-3.5 h-3.5 text-orange-400"></i>
                    <select id="f-rating" onchange="applyFilter()" class="text-[11px] font-bold text-gray-600 bg-transparent outline-none cursor-pointer">
                        <option value="">Semua Rating</option>
                        <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4★ ke atas</option>
                        <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3★ ke atas</option>
                        <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2★ ke atas</option>
                    </select>
                </div>

                {{-- Stok --}}
                <label class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-xl border border-gray-100 cursor-pointer">
                    <input type="checkbox" id="f-stock" value="1" {{ request('in_stock') ? 'checked' : '' }} onchange="applyFilter()" class="w-3.5 h-3.5 accent-[#E1700F]">
                    <span class="text-[11px] font-bold text-gray-600">Stok Tersedia</span>
                </label>

                {{-- Toggle Harga --}}
                <button type="button" @click="showPrice = !showPrice"
                    class="flex items-center gap-2 px-3 py-2 rounded-xl border transition text-[11px] font-bold"
                    :class="showPrice ? 'bg-orange-50 border-orange-200 text-[#E1700F]' : 'bg-gray-50 border-gray-100 text-gray-600'">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                    Filter Harga
                    <span x-show="{{ request()->hasAny(['min_price','max_price']) ? 'true' : 'false' }}" class="w-2 h-2 rounded-full bg-[#E1700F]"></span>
                </button>

                {{-- Reset --}}
                <button type="button" id="btn-reset" onclick="resetFilter()"
                    class="hidden items-center gap-1.5 px-3 py-2 rounded-xl border border-red-100 bg-red-50 text-red-500 text-[11px] font-bold hover:bg-red-100 transition">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i> Reset
                </button>

                {{-- Jumlah produk --}}
                <span id="product-count" class="ml-auto text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    {{ $products->count() }} Produk
                </span>
            </div>

            {{-- Filter Harga --}}
            <div x-show="showPrice" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">Harga</span>
                    <div class="flex items-center gap-2 flex-1">
                        <div class="flex items-center gap-1.5 px-3 py-2 bg-gray-50 rounded-xl border border-gray-100 flex-1">
                            <span class="text-[10px] text-gray-400 font-bold">Rp</span>
                            <input type="number" id="f-min-price" placeholder="Min" step="10000" min="0"
                                value="{{ request('min_price') }}"
                                class="w-full bg-transparent text-[11px] font-bold text-gray-700 outline-none">
                        </div>
                        <span class="text-gray-300 font-bold">—</span>
                        <div class="flex items-center gap-1.5 px-3 py-2 bg-gray-50 rounded-xl border border-gray-100 flex-1">
                            <span class="text-[10px] text-gray-400 font-bold">Rp</span>
                            <input type="number" id="f-max-price" placeholder="Max" step="10000" min="0"
                                value="{{ request('max_price') }}"
                                class="w-full bg-transparent text-[11px] font-bold text-gray-700 outline-none">
                        </div>
                        <button type="button" onclick="applyFilter()"
                            class="px-4 py-2 bg-[#E1700F] text-white rounded-xl text-[11px] font-black uppercase tracking-wider hover:bg-black transition whitespace-nowrap">
                            Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. SEMUA KOLEKSI --}}
    <div id="koleksi" class="container mx-auto px-4 mt-10">
        <div class="mb-8 border-b-2 border-gray-100 pb-5 flex items-center justify-between">
            <h3 class="text-3xl font-black text-gray-900 italic uppercase tracking-tighter flex items-center gap-3">
                <i data-lucide="box" class="w-6 h-6 text-[#E1700F]"></i>
                Semua Koleksi Murazon
            </h3>
        </div>

        <div id="product-grid-wrapper">
            @include('products._grid', ['products' => $products, 'hotItems' => $hotItems])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterBar() {
    return {
        showPrice: {{ request()->hasAny(['min_price', 'max_price']) ? 'true' : 'false' }},
    }
}

function buildParams() {
    const params = new URLSearchParams();
    const sort     = document.getElementById('f-sort').value;
    const category = document.getElementById('f-category').value;
    const rating   = document.getElementById('f-rating').value;
    const stock    = document.getElementById('f-stock').checked;
    const minPrice = document.getElementById('f-min-price').value;
    const maxPrice = document.getElementById('f-max-price').value;

    if (sort && sort !== 'latest') params.set('sort', sort); // ← Jangan set kalau default
    if (category && category !== 'semua') params.set('category', category);
    if (rating)        params.set('min_rating', rating);
    if (stock)         params.set('in_stock', '1');
    if (minPrice)      params.set('min_price', minPrice);
    if (maxPrice)      params.set('max_price', maxPrice);

    return params;
}

function checkReset(params) {
    const btn = document.getElementById('btn-reset');
    btn.classList.toggle('hidden', params.toString() === '');
    btn.classList.toggle('flex', params.toString() !== '');
}

async function applyFilter() {
    const params  = buildParams();
    const wrapper = document.getElementById('product-grid-wrapper');
    const counter = document.getElementById('product-count');

    checkReset(params);
    wrapper.classList.add('loading');

    try {
        const res  = await fetch('/?' + params.toString() + '&partial=1');
        const html = await res.text();
        wrapper.innerHTML = html;
        window.lucide?.createIcons();

        // Update jumlah produk
        const countMatch = html.match(/data-count="(\d+)"/);
        if (countMatch) counter.textContent = countMatch[1] + ' Produk';

        // Update URL tanpa reload
        history.replaceState(null, '', '/?' + params.toString());
    } catch(e) {
        console.error(e);
    } finally {
        wrapper.classList.remove('loading');
    }
}

function resetFilter() {
    document.getElementById('f-sort').value     = 'latest';
    document.getElementById('f-category').value = 'semua';
    document.getElementById('f-rating').value   = '';
    document.getElementById('f-stock').checked  = false;
    document.getElementById('f-min-price').value = '';
    document.getElementById('f-max-price').value = '';
    applyFilter();
}
</script>
@endpush