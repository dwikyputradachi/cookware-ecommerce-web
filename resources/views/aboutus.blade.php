@extends('layouts.app')
@section('title', 'Tentang Kami - Murazon')
@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-10">
    @include('partials._sidebar_about', ['active' => 'about'])
    <div class="flex-1 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">Tentang Murazon</h1>
        <div class="rounded-3xl overflow-hidden mb-8 shadow-lg">
            <img src="{{ asset('img/dummy-about.jpg') }}" class="w-full h-80 object-cover bg-gray-200" alt="About Murazon">
        </div>
        <p class="text-gray-600 leading-relaxed mb-4">Murazon adalah pusat perlengkapan dapur premium yang berfokus pada kualitas dan inovasi. Kami hadir untuk memenuhi kebutuhan peralatan masak modern keluarga Indonesia.</p>
        <p class="text-gray-600 leading-relaxed">Berdiri sejak 2026, kami berkomitmen memberikan produk original dengan pelayanan purna jual terbaik melalui jaringan distribusi yang luas.</p>
    </div>
</div>
@endsection