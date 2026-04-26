@php
    $perRow     = 5;
    $firstChunk = $products->take($perRow * 2);
    $restChunk  = $products->skip($perRow * 2);
@endphp

<span data-count="{{ $products->count() }}" class="hidden"></span>

{{-- Baris 1 & 2 --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-8">
    @forelse($firstChunk as $product)
        <x-product-card :product="$product" />
    @empty
        <div class="col-span-full py-32 text-center bg-white rounded-3xl border border-gray-100 shadow-inner">
            <div class="text-gray-200 mb-6 flex justify-center">
                <i data-lucide="package-search" class="w-24 h-24 stroke-[1]"></i>
            </div>
            <p class="text-gray-400 font-bold uppercase italic tracking-widest text-lg">Tidak ada produk yang cocok...</p>
        </div>
    @endforelse
</div>

{{-- HOT ITEM DIVIDER --}}
@if($hotItems->isNotEmpty())
<div class="flex items-center gap-4 my-10">
    <div class="flex-1 h-px bg-gray-200"></div>
    <div class="flex items-center gap-2 px-4 py-2 bg-red-600 rounded-full shadow-lg shadow-red-100">
        <i data-lucide="zap" class="w-3.5 h-3.5 text-white fill-white"></i>
        <span class="text-[10px] font-black text-white uppercase tracking-widest">Terlaris di Murazon</span>
        <i data-lucide="zap" class="w-3.5 h-3.5 text-white fill-white"></i>
    </div>
    <div class="flex-1 h-px bg-gray-200"></div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-8 mb-10">
    @foreach($hotItems as $product)
        <x-product-card :product="$product" />
    @endforeach
</div>
@endif

{{-- Sisa produk --}}
@if($restChunk->isNotEmpty())
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5 md:gap-8">
    @foreach($restChunk as $product)
        <x-product-card :product="$product" />
    @endforeach
</div>
@endif