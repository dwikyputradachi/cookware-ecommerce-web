@extends('admin.layout')

@section('title', 'Detail Pesanan #' . $order->id)
@section('page-title', 'Detail Pesanan #' . $order->id)

@section('content')

@php
    use Illuminate\Support\Str;

    function img_url($path) {
        if (!$path) return asset('img/no-image.png');

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return 'https://res.cloudinary.com/' . env('CLOUDINARY_CLOUD_NAME') . '/image/upload/' . $path;
    }
@endphp
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #1e40af;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 20px;
        transition: opacity 0.2s;
    }

    .back-link:hover { opacity: 0.75; }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
    }

    .left-col { display: flex; flex-direction: column; gap: 16px; }
    .right-col { display: flex; flex-direction: column; gap: 16px; }

    /* ── Card base ── */
    .detail-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 22px;
    }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-title i { color: #1e40af; }

    /* ── Status card ── */
    .status-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .status-header h3 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge.waiting  { background: #fef9c3; color: #854d0e; }
    .status-badge.done     { background: #dcfce7; color: #15803d; }
    .status-badge.rejected { background: #fee2e2; color: #b91c1c; }

    .meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .meta-item .meta-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 3px;
    }

    .meta-item .meta-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    /* ── Info list ── */
    .info-list { display: flex; flex-direction: column; gap: 12px; }

    .info-row .info-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 3px;
    }

    .info-row .info-value {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    /* ── Order items ── */
    .order-items { display: flex; flex-direction: column; gap: 8px; }

    .order-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: #f8fafc;
        border-radius: 9px;
        gap: 12px;
    }

    .order-item-name  { font-weight: 600; color: #1e293b; font-size: 13.5px; }
    .order-item-qty   { font-size: 12px; color: #64748b; margin-top: 2px; }
    .order-item-price { font-weight: 700; color: #1e293b; font-size: 13.5px; white-space: nowrap; }

    /* ── Payment proof ── */
    .proof-img {
        width: 100%;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        display: block;
    }

    .no-proof {
        background: #fefce8;
        border: 1px solid #fef08a;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #854d0e;
    }

    /* ── Action buttons ── */
    .verify-card {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .verify-card .card-title { margin-bottom: 0; }

    .btn-approve, .btn-reject {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        margin: 0;
        min-height: 48px;
    }

    .btn-approve:last-child,
    .btn-reject:last-child { margin-bottom: 0; }

    .btn-approve { background: #16a34a; color: white; }
    .btn-approve:hover { opacity: 0.88; transform: translateY(-1px); }

    .btn-reject  { background: #dc2626; color: white; }
    .btn-reject:hover  { opacity: 0.88; transform: translateY(-1px); }

    .already-verified {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #1d4ed8;
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        /* On mobile, show right col content below left */
        .right-col { order: -1; }
        .meta-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 480px) {
        .detail-card { padding: 16px; }
        .meta-grid { grid-template-columns: 1fr; gap: 10px; }
        .status-header { flex-direction: column; align-items: flex-start; }
    }
</style>

<a href="{{ route('admin.orders.index') }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
</a>

<div class="detail-grid">

    {{-- ── Left Column ── --}}
    <div class="left-col">

        {{-- Status Card --}}
        <div class="detail-card">
            <div class="status-header">
                <h3>Pesanan #{{ $order->id }}</h3>
                @if($order->status == 'waiting_verification')
                    <span class="status-badge waiting"><i class="fas fa-clock"></i> Menunggu Verifikasi</span>
                @elseif($order->status == 'completed')
                    <span class="status-badge done"><i class="fas fa-check-circle"></i> Disetujui</span>
                @elseif($order->status == 'cancelled')
                    <span class="status-badge rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                @endif
            </div>

            <div class="meta-grid">
                <div class="meta-item">
                    <p class="meta-label">Tanggal Pesanan</p>
                    <p class="meta-value">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="meta-item">
                    <p class="meta-label">Total Harga</p>
                    <p class="meta-value" style="font-size:16px;font-weight:800;color:#16a34a;">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="detail-card">
            <p class="card-title"><i class="fas fa-user"></i> Informasi Pelanggan</p>
            <div class="info-list">
                <div class="info-row">
                    <p class="info-label">Nama</p>
                    <p class="info-value">{{ $order->user->name ?? $order->customer_name ?? 'N/A' }}</p>
                </div>
                <div class="info-row">
                    <p class="info-label">Email</p>
                    <p class="info-value">{{ $order->user->email ?? $order->customer_phone ?? 'N/A' }}</p>
                </div>
                <div class="info-row">
                    <p class="info-label">Nomor WhatsApp</p>
                    <p class="info-value">{{ $order->customer_phone ?? 'N/A' }}</p>
                </div>
                <div class="info-row">
                    <p class="info-label">Alamat</p>
                    <p class="info-value">{{ $order->shipping_address ?? $order->customer_address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Order Items --}}
        <div class="detail-card">
            <p class="card-title"><i class="fas fa-shopping-bag"></i> Item Pesanan</p>
            <div class="order-items">
                @forelse($order->items as $item)
                    <div class="order-item">
                        <div>
                            <p class="order-item-name">{{ $item->product->name ?? 'Produk' }}</p>
                            <p class="order-item-qty">Qty: {{ $item->quantity }}</p>
                        </div>
                        <span class="order-item-price">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p style="color:#94a3b8;font-size:13px;">Tidak ada item dalam pesanan ini</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Right Column ── --}}
    <div class="right-col">

        {{-- Payment Proof --}}
        <div class="detail-card">
            <p class="card-title"><i class="fas fa-receipt"></i> Bukti Pembayaran</p>
            @if($order->payment_proof)
                <img src="{{ img_url($order->payment_proof) }}"
                     alt="Bukti Pembayaran" class="proof-img">
            @else
                <div class="no-proof">
                    <i class="fas fa-exclamation-triangle"></i>
                    Belum ada bukti pembayaran
                </div>
            @endif
        </div>

        {{-- Verify Actions --}}
        @if($order->status == 'waiting_verification')
            <div class="detail-card verify-card">
                <p class="card-title"><i class="fas fa-clipboard-check"></i> Verifikasi Pesanan</p>

                <form method="POST" action="{{ route('admin.orders.approve', $order->id) }}"
                      onsubmit="return confirm('Yakin ingin menyetujui pesanan ini?');">
                    @csrf
                    <button type="submit" class="btn-approve">
                        <i class="fas fa-check-circle"></i> Setujui Pesanan
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.orders.reject', $order->id) }}"
                      onsubmit="return confirm('Yakin ingin menolak pesanan ini?');">
                    @csrf
                    <button type="submit" class="btn-reject">
                        <i class="fas fa-times-circle"></i> Tolak Pesanan
                    </button>
                </form>
            </div>
        @else
            <div class="already-verified">
                <i class="fas fa-info-circle"></i>
                Pesanan sudah diverifikasi
            </div>
        @endif

    </div>
</div>

@endsection