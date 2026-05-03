@extends('admin.layout')

@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')

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

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #1e40af;
        color: white;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
        white-space: nowrap;
    }

    .btn-add:hover { opacity: 0.88; }

    /* ── Table card ── */
    
    .table-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    /* ── Desktop table ── */
    .products-table {
        width: 100%;
        border-collapse: collapse;
    }

    .products-table thead {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .products-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    .products-table th.center { text-align: center; }

    .products-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s;
    }

    .products-table tbody tr:last-child { border-bottom: none; }
    .products-table tbody tr:hover { background: #f8fafc; }

    .products-table td {
        padding: 14px 16px;
        font-size: 13.5px;
        color: #374151;
        vertical-align: middle;
    }

    .products-table td.center { text-align: center; }

    .product-thumb {
        width: 40px; height: 40px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .product-thumb-placeholder {
        width: 40px; height: 40px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; color: #94a3b8;
        flex-shrink: 0;
    }

    .product-name { font-weight: 600; color: #1e293b; }

    .stock-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .stock-badge.good   { background: #dcfce7; color: #15803d; }
    .stock-badge.warn   { background: #fef9c3; color: #854d0e; }
    .stock-badge.empty  { background: #fee2e2; color: #b91c1c; }

    .cod-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .cod-badge.yes { background: #dcfce7; color: #15803d; }
    .cod-badge.no  { background: #f1f5f9; color: #64748b; }

    .price-original { font-size: 11px; color: #94a3b8; text-decoration: line-through; }
    .price-promo    { font-weight: 700; color: #ea580c; font-size: 13.5px; }
    .price-discount-tag {
        display: inline-block;
        background: #ffedd5;
        color: #c2410c;
        font-size: 11px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 4px;
        margin-top: 2px;
    }

    .action-btns { display: flex; align-items: center; justify-content: center; gap: 6px; }

    .btn-edit, .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-edit   { background: #dbeafe; color: #1d4ed8; }
    .btn-edit:hover   { background: #bfdbfe; }
    .btn-delete { background: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background: #fecaca; }

    /* ── Empty state ── */
    .empty-state {
        padding: 48px 24px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i { font-size: 36px; display: block; margin-bottom: 12px; opacity: 0.4; }
    .empty-state p { font-size: 14px; margin: 0; }
    .empty-state a { color: #1e40af; text-decoration: none; font-weight: 600; }
    .empty-state a:hover { text-decoration: underline; }

    /* ── Mobile card layout ── */
    .mobile-cards { display: none; }

    .product-card {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .product-card:last-child { border-bottom: none; }

    .product-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .product-card-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 12px;
    }

    .meta-item { font-size: 12px; }
    .meta-item .meta-label { color: #94a3b8; font-weight: 500; margin-bottom: 2px; }
    .meta-item .meta-value { color: #1e293b; font-weight: 600; }

    .product-card-actions {
        display: flex;
        gap: 8px;
    }

    .product-card-actions .btn-edit,
    .product-card-actions .btn-delete {
        flex: 1;
        justify-content: center;
        padding: 8px 12px;
    }

    /* ── Pagination ── */
    .pagination-wrap { padding: 16px 20px; border-top: 1px solid #f1f5f9; }

    @media (max-width: 768px) {
        .products-table { display: none; }
        .mobile-cards   { display: block; }
    }

    @media (max-width: 480px) {
        .page-header h1 { font-size: 17px; }
    }
</style>

<div class="page-header">
    <h1>Daftar Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="btn-add">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>
</div>

<div class="table-card">

    {{-- ── Desktop Table ── --}}
    <table class="products-table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Kategori</th>
                <th class="center">COD</th>
                <th class="center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-thumb">
                            @else
                                <div class="product-thumb-placeholder"><i class="fas fa-image"></i></div>
                            @endif
                            <span class="product-name">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td>
                        @if($product->is_promo && $product->discount_price)
                            <p class="price-original">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="price-promo">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                            <span class="price-discount-tag">-{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%</span>
                        @else
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>
                        <span class="stock-badge {{ $product->stock > 10 ? 'good' : ($product->stock > 0 ? 'warn' : 'empty') }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>{{ $product->category ?? '-' }}</td>
                    <td class="center">
                        @if ($product->is_cod_available)
                            <span class="cod-badge yes"><i class="fas fa-check"></i> Ya</span>
                        @else
                            <span class="cod-badge no"><i class="fas fa-times"></i> Tidak</span>
                        @endif
                    </td>
                    <td class="center">
                        <div class="action-btns">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-edit">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus produk ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>Tidak ada produk. <a href="{{ route('admin.products.create') }}">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ── Mobile Cards ── --}}
    <div class="mobile-cards">
        @forelse ($products as $product)
            <div class="product-card">
                <div class="product-card-header">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-thumb">
                    @else
                        <div class="product-thumb-placeholder"><i class="fas fa-image"></i></div>
                    @endif
                    <div>
                        <p class="product-name" style="font-size:14px;">{{ $product->name }}</p>
                        <p style="font-size:12px;color:#64748b;margin-top:2px;">{{ $product->category ?? '-' }}</p>
                    </div>
                </div>

                <div class="product-card-meta">
                    <div class="meta-item">
                        <p class="meta-label">Harga</p>
                        @if($product->is_promo && $product->discount_price)
                            <p class="price-original" style="font-size:11px;">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="price-promo" style="font-size:13px;">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</p>
                        @else
                            <p class="meta-value">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @endif
                    </div>
                    <div class="meta-item">
                        <p class="meta-label">Stok</p>
                        <span class="stock-badge {{ $product->stock > 10 ? 'good' : ($product->stock > 0 ? 'warn' : 'empty') }}">
                            {{ $product->stock }}
                        </span>
                    </div>
                    <div class="meta-item">
                        <p class="meta-label">COD</p>
                        @if ($product->is_cod_available)
                            <span class="cod-badge yes"><i class="fas fa-check"></i> Ya</span>
                        @else
                            <span class="cod-badge no"><i class="fas fa-times"></i> Tidak</span>
                        @endif
                    </div>
                    @if($product->is_promo && $product->discount_price)
                        <div class="meta-item">
                            <p class="meta-label">Diskon</p>
                            <span class="price-discount-tag">-{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%</span>
                        </div>
                    @endif
                </div>

                <div class="product-card-actions">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-edit">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus produk ini?');" style="flex:1;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete" style="width:100%;justify-content:center;">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Tidak ada produk. <a href="{{ route('admin.products.create') }}">Tambah sekarang</a></p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
