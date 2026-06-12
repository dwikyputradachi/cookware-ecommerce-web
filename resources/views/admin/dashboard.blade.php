@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .page-heading { margin-bottom: 24px; }
    .page-heading h1 { font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 4px; }
    .page-heading p  { font-size: 13px; color: #64748b; margin: 0; }

    .stats-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 16px;
    }

    .stats-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .stat-sub {
        font-size: 11px;
        font-weight: 600;
        margin-top: 3px;
    }

    .quick-actions {
        background: white;
        border-radius: 14px;
        padding: 22px 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .quick-actions h3 {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 16px;
    }

    .actions-row {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.15s;
        white-space: nowrap;
    }

    .action-btn:hover { opacity: 0.85; transform: translateY(-1px); }

    .action-btn.primary { background: #1e40af; color: white; }
    .action-btn.warn    { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
    .action-btn.neutral { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    @media (max-width: 1024px) {
        .stats-grid-4 { grid-template-columns: repeat(2, 1fr); }
        .stats-grid-3 { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .stats-grid-4 { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stats-grid-3 { grid-template-columns: 1fr; gap: 12px; }
        .stat-value   { font-size: 20px !important; }
        .quick-actions { padding: 18px 16px; }
        .actions-row  { flex-direction: column; }
        .action-btn   { justify-content: center; }
    }

    @media (max-width: 380px) {
        .stats-grid-4 { grid-template-columns: 1fr; }
    }
</style>

<div class="page-heading">
    <h1>Selamat Datang, {{ auth('admin')->user()->name ?? 'Admin' }}</h1>
    <p>Berikut ringkasan aktivitas toko hari ini.</p>
</div>

{{-- Row 1: 4 cards --}}
<div class="stats-grid-4">
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
                <p class="stat-sub" style="color:#dc2626;">Perlu restok</p>
            @endif
        </div>
    </div>
</div>

{{-- Row 2: 3 cards --}}
<div class="stats-grid-3">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
        <div>
            <p class="stat-label">Pesanan Selesai</p>
            <p class="stat-value">{{ $totalOrders }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <p class="stat-label">Menunggu Verifikasi</p>
            <p class="stat-value">{{ $pendingOrders ?? 0 }}</p>
            @if(($pendingOrders ?? 0) > 0)
                <p class="stat-sub" style="color:#ea580c;">Perlu ditindak</p>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
        <div>
            <p class="stat-label">Total Revenue</p>
            <p class="stat-value" style="font-size:16px;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="quick-actions">
    <h3><i class="fas fa-bolt" style="color:#1e40af;margin-right:6px;"></i>Aksi Cepat</h3>
    <div class="actions-row">
        <a href="{{ route('admin.products.create') }}" class="action-btn primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
        <a href="{{ route('admin.orders.index') }}" class="action-btn warn">
            <i class="fas fa-clipboard-check"></i> Cek Pesanan Masuk
        </a>
        <a href="{{ route('admin.products.index') }}" class="action-btn neutral">
            <i class="fas fa-list"></i> Semua Produk
        </a>
    </div>
</div>

@endsection
