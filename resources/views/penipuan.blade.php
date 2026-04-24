@extends('layouts.app')
@section('title', 'Waspada Penipuan - Murazon')
@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-10">
    @include('partials._sidebar_about', ['active' => 'penipuan'])
    <div class="flex-1 max-w-4xl">
        <div class="bg-red-50 p-8 rounded-3xl border border-red-100">
            <h1 class="text-3xl font-bold text-red-700 mb-4 uppercase">Waspada Penipuan!</h1>
            <p class="text-gray-700 mb-6">Murazon hanya melakukan transaksi melalui akun resmi dan website ini. Kami tidak pernah meminta data kartu kredit atau kode OTP melalui telepon/WhatsApp.</p>
            <div class="bg-white p-4 rounded-xl text-sm font-bold text-red-600">
                Hotline Laporan: +62 812-703-0826
            </div>
        </div>
    </div>
</div>
@endsection