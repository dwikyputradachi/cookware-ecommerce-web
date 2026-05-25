@extends('admin.layout')
@section('page-title', 'Metode Pembayaran')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-900">Metode Pembayaran</h1>
    <p class="text-sm text-gray-400 mt-0.5">Kelola metode pembayaran yang tampil di halaman checkout</p>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    @foreach($payments as $p)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex gap-4 items-start">

        {{-- Icon / QR --}}
        <div class="w-14 h-14 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 overflow-hidden">
            @if($p->qr_image)
                <img src="{{ $p->qr_image }}" class="w-full h-full object-cover">
            @else
                @php
                    $icons = ['bca' => 'fa-building-columns', 'dana' => 'fa-wallet', 'qris' => 'fa-qrcode', 'cod' => 'fa-box'];
                    $colors = ['bca' => '#1e40af', 'dana' => '#3b82f6', 'qris' => '#dc2626', 'cod' => '#ea580c'];
                @endphp
                <i class="fas {{ $icons[$p->key] ?? 'fa-credit-card' }} text-xl"
                   style="color: {{ $colors[$p->key] ?? '#64748b' }}"></i>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2">
                <h3 class="font-bold text-gray-900 text-sm">{{ $p->label }}</h3>
                <form action="{{ route('admin.payments.toggle', $p) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="px-2.5 py-1 rounded-full text-xs font-bold transition
                            {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                        {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                    </button>
                </form>
            </div>
            @if($p->account_number)
            <p class="text-xs text-gray-500 mt-1 font-mono">{{ $p->account_number }}</p>
            @endif
            @if($p->account_name)
            <p class="text-xs text-gray-400">a/n {{ $p->account_name }}</p>
            @endif
        </div>

        {{-- Edit --}}
        <a href="{{ route('admin.payments.edit', $p) }}"
           class="shrink-0 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition">
            <i class="fas fa-pen"></i>
        </a>
    </div>
    @endforeach
</div>
@endsection