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

    .customer-name { font-weight: 600; color: #1e293b; }
    .customer-email { font-size: 12px; color: #64748b; margin-top: 2px; }

    .total-price {
        font-weight: 700;
        color: #16a34a;
        font-size: 14px;
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

    .status-badge.waiting  { background: #fef9c3; color: #854d0e; }
    .status-badge.done     { background: #dcfce7; color: #15803d; }
    .status-badge.rejected { background: #fee2e2; color: #b91c1c; }
    .status-badge.default  { background: #f1f5f9; color: #475569; }

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
    }

    .btn-view:hover { background: #bfdbfe; }

    .empty-state {
        padding: 48px 24px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i { font-size: 36px; display: block; margin-bottom: 12px; opacity: 0.4; }
    .empty-state p { font-size: 14px; margin: 0; }

    /* ── Mobile cards ── */
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
        margin-bottom: 10px;
    }

    .order-card-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
    }

    .meta-item { font-size: 12px; }
    .meta-label { color: #94a3b8; font-weight: 500; margin-bottom: 2px; }
    .meta-value { color: #1e293b; font-weight: 600; font-size: 13px; }

    @media (max-width: 768px) {
        .orders-table { display: none; }
        .mobile-cards { display: block; }
    }

    @media (max-width: 480px) {
        .page-header h1 { font-size: 17px; }
    }
</style>

<div class="page-header">
    <h1>Daftar Pesanan</h1>
</div>

<div class="table-card">

    {{-- Desktop Table --}}
    <table class="orders-table">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Total Harga</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th class="center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><span class="order-id">#{{ $order->id }}</span></td>
                    <td>
                        <p class="customer-name">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                        <p class="customer-email">{{ $order->user->email ?? 'N/A' }}</p>
                    </td>
                    <td><span class="total-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></td>
                    <td style="white-space:nowrap;">{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td>
                        @if($order->status == 'waiting_verification')
                            <span class="status-badge waiting"><i class="fas fa-clock"></i> Menunggu</span>
                        @elseif($order->status == 'completed')
                            <span class="status-badge done"><i class="fas fa-check-circle"></i> Disetujui</span>
                        @elseif($order->status == 'cancelled')
                            <span class="status-badge rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                        @else
                            <span class="status-badge default">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-view">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada pesanan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Mobile Cards --}}
    <div class="mobile-cards">
        @forelse ($orders as $order)
            <div class="order-card">
                <div class="order-card-header">
                    <span class="order-id">#{{ $order->id }}</span>
                    @if($order->status == 'waiting_verification')
                        <span class="status-badge waiting"><i class="fas fa-clock"></i> Menunggu</span>
                    @elseif($order->status == 'completed')
                        <span class="status-badge done"><i class="fas fa-check-circle"></i> Disetujui</span>
                    @elseif($order->status == 'cancelled')
                        <span class="status-badge rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                    @else
                        <span class="status-badge default">{{ ucfirst($order->status) }}</span>
                    @endif
                </div>

                <div class="order-card-body">
                    <div class="meta-item">
                        <p class="meta-label">Pelanggan</p>
                        <p class="meta-value">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                    </div>
                    <div class="meta-item">
                        <p class="meta-label">Total</p>
                        <p class="meta-value" style="color:#16a34a;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="meta-item" style="grid-column:span 2;">
                        <p class="meta-label">Tanggal</p>
                        <p class="meta-value" style="font-weight:500;color:#475569;">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-view" style="width:100%;justify-content:center;">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada pesanan</p>
            </div>
        @endforelse
    </div>

</div>

@endsection