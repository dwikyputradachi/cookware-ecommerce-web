@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Products --}}
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Produk</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
            </div>
        </div>

        {{-- Total Stock --}}
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-cubes"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Stok</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalStock }}</p>
            </div>
        </div>

        {{-- COD Available --}}
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Produk COD</p>
                <p class="text-2xl font-bold text-gray-900">{{ $codAvailableProducts }}</p>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Stok Rendah</p>
                <p class="text-2xl font-bold text-gray-900">{{ $lowStockProducts }}</p>
            </div>
        </div>
    </div>

    {{-- Order Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Pesanan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    
    <div class="mt-8 bg-white rounded-lg p-6 shadow-sm border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex gap-4">
            <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i>
                Tambah Produk
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2">
                <i class="fas fa-list"></i>
                Lihat Semua Produk
            </a>
        </div>
    </div>
@endsection