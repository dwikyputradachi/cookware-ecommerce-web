@extends('layouts.app')
@section('title', 'Panduan - Murazon')
@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-10">
    @include('partials._sidebar_about', ['active' => 'panduan'])
    <div class="flex-1 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">Panduan Belanja Online</h1>
        <div class="prose text-gray-600">
            <p>1. Pilih produk yang Anda inginkan.</p>
            <p>2. Klik tombol "Tambah ke Keranjang".</p>
            <p>3. Masukkan data pengiriman dengan lengkap.</p>
            <p>4. Lakukan pembayaran dan pesanan Anda akan segera kami proses.</p>
        </div>
    </div>
</div>
@endsection