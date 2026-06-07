@extends('admin.layout')

@section('title', 'Verifikasi Pesanan')
@section('page-title', 'Verifikasi Pesanan')

@section('content')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }

    .page-header h1 {
        font-size: 20px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
    }

    .page-header p {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0 0;
    }

    .filter-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 16px;
        margin-bottom: 16px;
    }

    .filter-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-input,
    .filter-select {
        height: 40px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0 12px;
        font-size: 13px;
        color: #334155;
        background: #f8fafc;
        outline: none;
    }

    .filter-input {
        min-width: 240px;
        flex: 1;
    }

    .filter-input:focus,
    .filter-select:focus {
        border-color: #2563eb;
        background: white;
    }

    .btn-filter,
    .btn-reset {
        height: 40px;
        border: none;
        border-radius: 10px;
        padding: 0 14px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-filter {
        background: #1e40af;
        color: white;
    }

    .btn-reset {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .table-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .orders-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .orders-table th.center { text-align: center; }

    .orders-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }

    .orders-table tbody tr:last-child { border-bottom: none; }
    .orders-table tbody tr:hover { background: #f8fafc; }

    .orders-table td {
        padding: 14px 16px;
        font-size: 13.5px;
        color: #374151;
        vertical-align: middle;
    }

    .orders-table td.center { text-align: center; }

    .order-id {
        font-weight: 700;
        color: #1e293b;
        font-size: 14px;
    }

    .customer-name {
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .customer-contact {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .total-price {
        font-weight: 700;
        color: #16a34a;
        font-size: 14px;
        white-space: nowrap;
    }

    .payment-method {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        padding: 4px 9px;
        border-radius: 999px;
        display: inline-flex;
        width: fit-content;
        text-transform: uppercase;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.pending  { background: #dbeafe; color: #1d4ed8; }
    .status-badge.waiting  { background: #fef9c3; color: #854d0e; }
    .status-badge.done     { background: #dcfce7; color: #15803d; }
    .status-badge.rejected { background: #fee2e2; color: #b91c1c; }
    .status-badge.default  { background: #f1f5f9; color: #475569; }

    .archive-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 999px;
        white-space: nowrap;
        margin-top: 5px;
    }

    .proof-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        color: #15803d;
        background: #dcfce7;
        padding: 4px 8px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .proof-indicator.none {
        color: #854d0e;
        background: #fef9c3;
    }

    .btn-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        background: #dbeafe;
        color: #1d4ed8;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        white-space: nowrap;
        border: none;
        cursor: pointer;
    }

    .btn-view:hover { background: #bfdbfe; }

    .btn-archive {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-archive:hover {
        background: #e2e8f0;
    }

    .btn-restore {
        background: #dcfce7;
        color: #15803d;
    }

    .btn-restore:hover {
        background: #bbf7d0;
    }

    .empty-state {
        padding: 48px 24px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 36px;
        display: block;
        margin-bottom: 12px;
        opacity: 0.4;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }

    .mobile-cards { display: none; }

    .order-card {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .order-card:last-child { border-bottom: none; }

    .order-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
    }

    .order-card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
    }

    .meta-item { font-size: 12px; }

    .meta-label {
        color: #94a3b8;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .meta-value {
        color: #1e293b;
        font-weight: 600;
        font-size: 13px;
    }

    .action-row {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pagination-wrap {
        padding: 16px;
        border-top: 1px solid #f1f5f9;
        background: #fff;
    }

    @media (max-width: 768px) {
        .orders-table { display: none; }
        .mobile-cards { display: block; }
        .filter-form { flex-direction: column; align-items: stretch; }
        .filter-input { min-width: 0; width: 100%; }
        .filter-select, .btn-filter, .btn-reset { width: 100%; justify-content: center; }
    }

    @media (max-width: 480px) {
        .page-header h1 { font-size: 17px; }
        .order-card-body { grid-template-columns: 1fr; }
    }
</style>

@php
    if (!function_exists('orderStatusBadge')) {
        function orderStatusBadge($status) {
            return match ($status) {
                'pending' => [
                    'class' => 'pending',
                    'icon' => 'fas fa-hourglass-half',
                    'label' => 'Pending',
                ],
                'waiting_verification' => [
                    'class' => 'waiting',
                    'icon' => 'fas fa-clock',
                    'label' => 'Menunggu',
                ],
                'completed' => [
                    'class' => 'done',
                    'icon' => 'fas fa-check-circle',
                    'label' => 'Disetujui',
                ],
                'cancelled' => [
                    'class' => 'rejected',
                    'icon' => 'fas fa-times-circle',
                    'label' => 'Ditolak',
                ],
                default => [
                    'class' => 'default',
                    'icon' => 'fas fa-circle-info',
                    'label' => ucfirst(str_replace('_', ' ', $status)),
                ],
            };
        }
    }
@endphp

<div class="page-header">
    <div>
        <h1>Daftar Pesanan</h1>
        <p>Kelola pesanan masuk tanpa menghapus data transaksi dan review pelanggan.</p>
    </div>
</div>

<div class="filter-card">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="filter-form">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari ID, nama pelanggan, atau nomor WA..."
            class="filter-input">

        <select name="status" class="filter-select">
            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="waiting_verification" {{ request('status') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending / COD</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Disetujui</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Ditolak</option>
        </select>

        <select name="archive" class="filter-select">
            <option value="active" {{ request('archive', 'active') === 'active' ? 'selected' : '' }}>Order Aktif</option>
            <option value="archived" {{ request('archive') === 'archived' ? 'selected' : '' }}>Order Arsip</option>
            <option value="all" {{ request('archive') === 'all' ? 'selected' : '' }}>Semua Order</option>
        </select>

        <button type="submit" class="btn-filter">
            <i class="fas fa-search"></i> Filter
        </button>

        @if(request()->hasAny(['search', 'status', 'archive']) && (request('search') || request('status') !== 'all' || request('archive', 'active') !== 'active'))
            <a href="{{ route('admin.orders.index') }}" class="btn-reset">
                <i class="fas fa-rotate-left"></i> Reset
            </a>
        @endif
    </form>
</div>

<div class="table-card">

    <table class="orders-table">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Bukti</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th class="center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($orders as $order)
                @php
                    $badge = orderStatusBadge($order->status);
                @endphp

                <tr>
                    <td>
                        <span class="order-id">#{{ $order->id }}</span>

                        @if($order->archived_at)
                            <div class="archive-badge">
                                <i class="fas fa-box-archive"></i> Arsip
                            </div>
                        @endif
                    </td>

                    <td>
                        <p class="customer-name">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                        <p class="customer-contact">{{ $order->customer_phone ?? $order->user->email ?? 'N/A' }}</p>
                    </td>

                    <td>
                        <span class="total-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </td>

                    <td>
                        <span class="payment-method">{{ strtoupper($order->payment_method ?? '-') }}</span>
                    </td>

                    <td>
                        @if($order->payment_proof)
                            <span class="proof-indicator">
                                <i class="fas fa-check"></i> Ada
                            </span>
                        @else
                            <span class="proof-indicator none">
                                <i class="fas fa-minus"></i> Tidak ada
                            </span>
                        @endif
                    </td>

                    <td style="white-space:nowrap;">
                        {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                    </td>

                    <td>
                        <span class="status-badge {{ $badge['class'] }}">
                            <i class="{{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                        </span>
                    </td>

                    <td class="center">
                        <div class="action-row">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-view">
                                <i class="fas fa-eye"></i> Detail
                            </a>

                            @if(!$order->archived_at && in_array($order->status, ['completed', 'cancelled']))
                                <form method="POST"
                                      action="{{ route('admin.orders.archive', $order->id) }}"
                                      onsubmit="return confirm('Arsipkan pesanan ini? Data transaksi dan review tetap aman.');">
                                    @csrf

                                    <button type="submit" class="btn-view btn-archive">
                                        <i class="fas fa-box-archive"></i> Arsip
                                    </button>
                                </form>
                            @endif

                            @if($order->archived_at)
                                <form method="POST"
                                      action="{{ route('admin.orders.restore', $order->id) }}"
                                      onsubmit="return confirm('Pulihkan pesanan dari arsip?');">
                                    @csrf

                                    <button type="submit" class="btn-view btn-restore">
                                        <i class="fas fa-rotate-left"></i> Pulihkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada pesanan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mobile-cards">
        @forelse ($orders as $order)
            @php
                $badge = orderStatusBadge($order->status);
            @endphp

            <div class="order-card">
                <div class="order-card-header">
                    <div>
                        <span class="order-id">#{{ $order->id }}</span>

                        @if($order->archived_at)
                            <div class="archive-badge">
                                <i class="fas fa-box-archive"></i> Arsip
                            </div>
                        @endif
                    </div>

                    <span class="status-badge {{ $badge['class'] }}">
                        <i class="{{ $badge['icon'] }}"></i> {{ $badge['label'] }}
                    </span>
                </div>

                <div class="order-card-body">
                    <div class="meta-item">
                        <p class="meta-label">Pelanggan</p>
                        <p class="meta-value">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                    </div>

                    <div class="meta-item">
                        <p class="meta-label">Kontak</p>
                        <p class="meta-value">{{ $order->customer_phone ?? $order->user->email ?? 'N/A' }}</p>
                    </div>

                    <div class="meta-item">
                        <p class="meta-label">Total</p>
                        <p class="meta-value" style="color:#16a34a;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="meta-item">
                        <p class="meta-label">Metode</p>
                        <p class="meta-value">{{ strtoupper($order->payment_method ?? '-') }}</p>
                    </div>

                    <div class="meta-item">
                        <p class="meta-label">Bukti</p>
                        <p class="meta-value">
                            {{ $order->payment_proof ? 'Ada bukti' : 'Tidak ada bukti' }}
                        </p>
                    </div>

                    <div class="meta-item">
                        <p class="meta-label">Tanggal</p>
                        <p class="meta-value" style="font-weight:500;color:#475569;">
                            {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.orders.show', $order->id) }}"
                   class="btn-view"
                   style="width:100%;justify-content:center;">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>

                @if(!$order->archived_at && in_array($order->status, ['completed', 'cancelled']))
                    <form method="POST"
                          action="{{ route('admin.orders.archive', $order->id) }}"
                          onsubmit="return confirm('Arsipkan pesanan ini? Data transaksi dan review tetap aman.');"
                          style="margin-top:8px;">
                        @csrf

                        <button type="submit"
                                class="btn-view btn-archive"
                                style="width:100%;justify-content:center;">
                            <i class="fas fa-box-archive"></i> Arsipkan Pesanan
                        </button>
                    </form>
                @endif

                @if($order->archived_at)
                    <form method="POST"
                          action="{{ route('admin.orders.restore', $order->id) }}"
                          onsubmit="return confirm('Pulihkan pesanan dari arsip?');"
                          style="margin-top:8px;">
                        @csrf

                        <button type="submit"
                                class="btn-view btn-restore"
                                style="width:100%;justify-content:center;">
                            <i class="fas fa-rotate-left"></i> Pulihkan Pesanan
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada pesanan</p>
            </div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="pagination-wrap">
            {{ $orders->links() }}
        </div>
    @endif

</div>

@endsection