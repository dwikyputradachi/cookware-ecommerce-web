@props(['product'])

<a href="/products/{{ $product->id }}" class="product-card group bg-white rounded-2xl border border-gray-100 overflow-hidden flex flex-col relative">
    @if($product->is_cod_available)
        <div class="absolute top-2 left-2 z-10 bg-black/70 backdrop-blur-md text-white text-[8px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 uppercase">
            <i data-lucide="truck" class="w-3 h-3"></i> COD
        </div>
    @endif

    <div class="aspect-square bg-gray-50 overflow-hidden flex items-center justify-center p-2 relative">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
        @else
            <i data-lucide="cooking-pot" class="w-12 h-12 text-gray-200"></i>
        @endif
    </div>

    <div class="p-4 flex flex-col grow">
        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter mb-1">{{ $product->category }}</span>
        <h2 class="text-xs md:text-sm font-bold text-gray-800 line-clamp-2 h-8 md:h-10 mb-2 leading-tight">
            {{ $product->name }}
        </h2>
        <div class="mt-auto">
            <p class="text-sm md:text-base font-black text-[#E1700F]">Rp {{ number_format($product->price) }}</p>
        </div>
    </div>
</a>