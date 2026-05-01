@extends('admin.layout')

@section('title', 'Detail Pesanan #' . $order->id)
@section('page-title', 'Detail Pesanan #' . $order->id)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 font-medium">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pesanan #{{ $order->id }}</h3>
                    @if($order->status == 'waiting_verification')
                        <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-medium flex items-center gap-2">
                            <i class="fas fa-clock"></i> Menunggu Verifikasi
                        </span>
                    @elseif($order->status == 'completed')
                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Disetujui
                        </span>
                    @elseif($order->status == 'cancelled')
                        <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Ditolak
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Tanggal Pesanan</p>
                        <p class="text-gray-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total Harga</p>
                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i> Informasi Pelanggan
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Nama</p>
                        <p class="text-gray-900">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Email</p>
                        <p class="text-gray-900">{{ $order->user->email ?? $order->customer_phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Nomor WhatsApp</p>
                        <p class="text-gray-900">{{ $order->customer_phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Alamat</p>
                        <p class="text-gray-900">{{ $order->shipping_address ?? $order->customer_address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-shopping-bag text-blue-600"></i> Item Pesanan
                </h3>
                
                <div class="space-y-3">
                    @forelse($order->items as $item)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $item->product->name ?? 'Produk' }}</p>
                                <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="font-semibold text-gray-900">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <p class="text-gray-600">Tidak ada item dalam pesanan ini</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="space-y-6">
            {{-- Payment Proof --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-receipt text-blue-600"></i> Bukti Pembayaran
                </h3>
                
                @if($order->payment_proof)
                    <div class="bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ str_contains($order->payment_proof ?? '', 'http') ? $order->payment_proof : asset('storage/' . $order->payment_proof) }}"
                             alt="Bukti Pembayaran" 
                             class="w-full h-auto object-cover rounded-lg">
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        <p class="text-yellow-800">Belum ada bukti pembayaran</p>
                    </div>
                @endif
            </div>

            {{-- Action Buttons --}}
            @if($order->status == 'waiting_verification')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Verifikasi Pesanan</h3>
                    
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('admin.orders.approve', $order->id) }}" 
                              onsubmit="return confirm('Yakin ingin menyetujui pesanan ini?');">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-check-circle"></i> Setujui Pesanan
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.orders.reject', $order->id) }}" 
                              onsubmit="return confirm('Yakin ingin menolak pesanan ini?');">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-times-circle"></i> Tolak Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center gap-3">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    <p class="text-blue-800 text-sm">Pesanan sudah diverifikasi</p>
                </div>
            @endif
        </div>
    </div>
@endsection