@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Page heading --}}
<div class="mb-6">
    <h1 style="font-size:20px;font-weight:800;color:#1e293b;">Selamat Datang, {{ auth('admin')->user()->name ?? 'Admin' }} </h1>
    <p style="font-size:13px;color:#64748b;margin-top:3px;">Berikut ringkasan aktivitas toko hari ini.</p>
</div>

{{-- Stat cards row 1 --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-box"></i></div>
        <div>
            <p class="stat-label">Total Produk</p>
            <p class="stat-value">{{ $totalProducts }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-cubes"></i></div>
        <div>
            <p class="stat-label">Total Stok</p>
            <p class="stat-value">{{ $totalStock }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-motorcycle"></i></div>
        <div>
            <p class="stat-label">Produk COD</p>
            <p class="stat-value">{{ $codAvailableProducts }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
            <p class="stat-label">Stok Rendah</p>
            <p class="stat-value">{{ $lowStockProducts }}</p>
            @if($lowStockProducts > 0)
                <p style="font-size:11px;color:#dc2626;margin-top:2px;font-weight:600;">Perlu restok</p>
            @endif
        </div>
    </div>
</div>

{{-- Stat cards row 2 --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <p class="stat-label">Total Pesanan Selesai</p>
            <p class="stat-value">{{ $totalOrders }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <p class="stat-label">Menunggu Verifikasi</p>
            <p class="stat-value">{{ $pendingOrders ?? 0 }}</p>
            @if(($pendingOrders ?? 0) > 0)
                <p style="font-size:11px;color:#ea580c;margin-top:2px;font-weight:600;">Perlu ditindak</p>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <p class="stat-label">Total Revenue</p>
            <p class="stat-value" style="font-size:17px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div style="background:white;border-radius:14px;padding:24px;border:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    <h3 style="font-size:14px;font-weight:700;color:#1e293b;margin-bottom:16px;">
        <i class="fas fa-bolt" style="color:#1e40af;margin-right:6px;"></i>Aksi Cepat
    </h3>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.products.create') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#1e40af;color:white;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:opacity 0.2s;"
           onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
        <a href="{{ route('admin.orders.index') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:opacity 0.2s;"
           onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
            <i class="fas fa-clipboard-check"></i> Cek Pesanan Masuk
        </a>
        <a href="{{ route('admin.products.index') }}"
           style="display:inline-flex;align-items:center;gap:8px;padding:10px 18px;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:9px;font-size:13px;font-weight:600;text-decoration:none;transition:opacity 0.2s;"
           onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
            <i class="fas fa-list"></i> Semua Produk
        </a>
    </div>
</div>

@endsection