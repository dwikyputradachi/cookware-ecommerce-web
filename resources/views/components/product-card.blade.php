@props(['product'])

@php
    $imgUrl = str_contains($product->image ?? '', 'http') 
        ? $product->image 
        : asset('storage/' . $product->image);
@endphp

<a href="/products/{{ $product->id }}" class="product-card group bg-white rounded-2xl border border-gray-100 overflow-hidden flex flex-col relative transition-all duration-300 hover:shadow-lg hover:border-gray-200">
    
    <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
        @if($product->is_cod_available)
            <div class="bg-black/70 backdrop-blur-md text-white text-[8px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 uppercase w-fit">
                <i data-lucide="truck" class="w-3 h-3"></i> COD
            </div>
        @endif
        @if($product->is_promo)
            <div class="bg-red-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 uppercase w-fit shadow-sm">
                PROMO
            </div>
        @endif
    </div>

    <div class="aspect-square bg-gray-50 overflow-hidden flex items-center justify-center p-2 relative">
        @if($product->image)
            <img src="{{ $imgUrl }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="flex flex-col items-center opacity-20">
                <i data-lucide="cooking-pot" class="w-12 h-12"></i>
            </div>
        @endif
    </div>

    <div class="p-3 md:p-4 flex flex-col grow">
        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter mb-1">{{ $product->category }}</span>
        <h2 class="text-xs md:text-sm font-bold text-gray-800 line-clamp-2 h-8 md:h-10 mb-1 leading-tight group-hover:text-[#E1700F] transition-colors">
            {{ $product->name }}
        </h2>
        <div class="flex items-center gap-1.5 mb-3">
            <div class="flex items-center text-orange-400 gap-0.5">
                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                <span class="text-[10px] font-bold text-gray-700">{{ number_format($product->rating ?? 5.0, 1) }}</span>
            </div>
            <span class="text-gray-200 text-[10px]">|</span>
            <span class="text-[10px] text-gray-500 font-medium">Terjual {{ $product->total_sold ?? 0 }}+</span>
        </div>
        <div class="mt-auto">
            @if($product->is_promo && $product->discount_price)
                <p class="text-[10px] text-gray-400 line-through leading-none mb-0.5">Rp {{ number_format($product->price) }}</p>
                <p class="text-sm md:text-base font-black text-red-600">Rp {{ number_format($product->discount_price) }}</p>
            @else
                <p class="text-sm md:text-base font-black text-[#E1700F]">Rp {{ number_format($product->price) }}</p>
            @endif
        </div>
    </div>
</a>