@extends('layouts.app')
@section('title', 'Bantuan - Murazon')
@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-10">
    @include('partials._sidebar_about', ['active' => 'bantuan'])
    <div class="flex-1 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">Bantuan Belanja</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-5 border border-gray-100 rounded-2xl hover:border-orange-500 transition-all cursor-pointer">
                <h4 class="font-bold mb-1">Cara Order</h4>
                <p class="text-xs text-gray-500">Panduan langkah demi langkah melakukan pemesanan.</p>
            </div>
            <div class="p-5 border border-gray-100 rounded-2xl hover:border-orange-500 transition-all cursor-pointer">
                <h4 class="font-bold mb-1">Pembayaran</h4>
                <p class="text-xs text-gray-500">Informasi metode transfer dan konfirmasi otomatis.</p>
            </div>
        </div>
    </div>
</div>
@endsection