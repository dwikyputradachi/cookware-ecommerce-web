@props(['product'])

@php
    $image = $product->image ?? null;
    $imgUrl = null;

    if ($image) {
        $imgUrl = str_contains($image, 'http')
            ? $image
            : asset('storage/' . $image);

        if (str_contains($imgUrl, 'res.cloudinary.com') && str_contains($imgUrl, '/image/upload/')) {
            $imgUrl = str_replace(
                '/image/upload/',
                '/image/upload/f_auto,q_auto:good,w_600,c_limit/',
                $imgUrl
            );
        }
    }

    $price = (float) ($product->price ?? 0);
    $discountPrice = (float) ($product->discount_price ?? 0);

    $isDiscounted = $product->is_promo
        && $discountPrice > 0
        && $discountPrice < $price;

    $finalPrice = $isDiscounted ? $discountPrice : $price;

    $reviewsCount = (int) ($product->reviews_count ?? 0);
    $avgRating = $product->reviews_avg_rating ?? $product->rating ?? 0;
    $rating = $reviewsCount > 0 ? (float) $avgRating : 0.0;

    $totalSold = $product->total_sold ?? 0;
    $stock = $product->stock ?? 0;
@endphp

<a href="/products/{{ $product->id }}"
   class="product-card group bg-white rounded-2xl border border-gray-100 overflow-hidden flex flex-col relative transition-all duration-300 hover:shadow-lg hover:border-gray-200">

    <div class="absolute top-2 left-2 z-10 flex flex-col gap-1">
        @if($product->is_cod_available)
            <div class="bg-black/70 backdrop-blur-md text-white text-[8px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 uppercase w-fit">
                <i data-lucide="truck" class="w-3 h-3"></i> COD
            </div>
        @endif

        @if($isDiscounted)
            <div class="bg-red-500 text-white text-[8px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 uppercase w-fit shadow-sm">
                PROMO
            </div>
        @endif

        @if($stock <= 0)
            <div class="bg-gray-900 text-white text-[8px] font-bold px-2 py-0.5 rounded-md uppercase w-fit shadow-sm">
                Habis
            </div>
        @endif
    </div>

    <div class="aspect-square bg-gray-50 overflow-hidden flex items-center justify-center p-2 relative">
        @if($imgUrl)
            <img src="{{ $imgUrl }}"
                 alt="{{ $product->name }}"
                 loading="lazy"
                 decoding="async"
                 class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
        @else
            <div class="flex flex-col items-center opacity-20">
                <i data-lucide="cooking-pot" class="w-12 h-12"></i>
            </div>
        @endif
    </div>

    <div class="p-3 md:p-4 flex flex-col grow">
        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter mb-1">
            {{ $product->category ?? 'Cookware' }}
        </span>

        <h2 class="text-xs md:text-sm font-bold text-gray-800 line-clamp-2 h-8 md:h-10 mb-1 leading-tight group-hover:text-[#E1700F] transition-colors">
            {{ $product->name }}
        </h2>

        <div class="flex items-center gap-1.5 mb-3">
            <div class="flex items-center text-orange-400 gap-0.5">
                <i data-lucide="star" class="w-3 h-3 fill-current"></i>
                <span class="text-[10px] font-bold text-gray-700">
                    {{ number_format($rating, 1) }}
                </span>
            </div>

            <span class="text-gray-200 text-[10px]">|</span>

            <span class="text-[10px] text-gray-500 font-medium">
                Terjual {{ number_format($totalSold) }}+
            </span>
        </div>

        <div class="mt-auto">
            @if($isDiscounted)
                <p class="text-[10px] text-gray-400 line-through leading-none mb-0.5">
                    Rp {{ number_format($price, 0, ',', '.') }}
                </p>

                <p class="text-sm md:text-base font-black text-red-600">
                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </p>
            @else
                <p class="text-sm md:text-base font-black text-[#E1700F]">
                    Rp {{ number_format($finalPrice, 0, ',', '.') }}
                </p>
            @endif
        </div>
    </div>
</a>