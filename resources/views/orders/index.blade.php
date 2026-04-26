@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Murazon')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-3xl">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="p-3 bg-orange-50 rounded-2xl text-[#E1700F]">
            <i data-lucide="package" class="w-6 h-6"></i>
        </div>
        <div>
            <h1 class="text-xl font-black text-gray-900 uppercase tracking-tight italic">Riwayat Pesanan</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Cek status & beri ulasan produkmu</p>
        </div>
    </div>

    {{-- Form Cari --}}
    <form action="{{ route('orders.search') }}" method="POST"
          class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-8">
        @csrf
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Masukkan No. WhatsApp</p>
        <div class="flex gap-3">
            <input type="number" name="phone"
                   value="{{ old('phone', request('phone')) }}"
                   placeholder="Masukkan nomor WA yang kamu gunakan saat order... : 08xxxxxxxx"
                   class="flex-1 px-4 py-3 rounded-xl border border-gray-100 text-sm font-bold focus:ring-1 focus:ring-[#E1700F] outline-none bg-gray-50">
            <button type="submit"
                    class="px-6 py-3 bg-[#E1700F] text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition">
                Cari
            </button>
        </div>
        @error('phone')
            <p class="text-xs text-red-500 font-bold mt-2">{{ $message }}</p>
        @enderror
    </form>

    {{-- Hasil Pencarian --}}
    @isset($orders)
        @if($orders->isEmpty())
            <div class="text-center py-16 border-2 border-dashed border-gray-100 rounded-[2rem]">
                <i data-lucide="package-search" class="w-10 h-10 text-gray-200 mx-auto mb-3"></i>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tidak ada pesanan ditemukan</p>
                <p class="text-[10px] text-gray-400 mt-1">Pastikan nomor WA yang kamu masukkan benar.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">

                    {{-- Header Order --}}
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-50">
                        <div>
                            <p class="text-xs font-black text-gray-800">Order #{{ $order->id }}</p>
                            <p class="text-[10px] text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider
                            @if($order->status == 'completed') bg-green-100 text-green-700
                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($order->status == 'waiting_verification') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-500 @endif">
                            @if($order->status == 'completed') Selesai
                            @elseif($order->status == 'pending') Menunggu
                            @elseif($order->status == 'waiting_verification') Menunggu Verifikasi
                            @else {{ $order->status }} @endif
                        </span>
                    </div>

                    {{-- List Produk --}}
                    <div class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                        @if($item->product)
                        <div class="flex items-center gap-4 px-5 py-4">
                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                 class="w-14 h-14 object-contain bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">
                                    {{ $item->quantity }}x • Rp {{ number_format($item->price) }}
                                </p>
                            </div>

                            {{-- Tombol Beri Ulasan (hanya muncul kalau order selesai) --}}
                            @if($order->status == 'completed')
                                @php
                                    $sudahReview = \App\Models\Review::where('order_id', $order->id)
                                                    ->where('product_id', $item->product_id)
                                                    ->exists();
                                @endphp
                                @if($sudahReview)
                                    <span class="text-[9px] font-black text-green-600 uppercase flex items-center gap-1">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Diulas
                                    </span>
                                @else
                                    <a href="/products/{{ $item->product_id }}?order_id={{ $order->id }}"
                                       class="px-3 py-2 bg-orange-50 text-[#E1700F] border border-orange-100 rounded-xl text-[9px] font-black uppercase tracking-tight hover:bg-[#E1700F] hover:text-white transition whitespace-nowrap">
                                        Beri Ulasan
                                    </a>
                                @endif
                            @endif
                        </div>
                        @endif
                        @endforeach
                    </div>

                    {{-- Footer Total --}}
                    <div class="flex items-center justify-between px-5 py-4 bg-gray-50 border-t border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">
                            {{ strtoupper($order->payment_method) }}
                        </p>
                        <p class="text-sm font-black text-[#E1700F] italic">
                            Rp {{ number_format($order->total_price) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    @endisset

</div>
@endsection