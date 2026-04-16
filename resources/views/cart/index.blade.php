<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Santo Cookware</title>
    @vite(['resources/css/app.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">

<nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
        <a href="/" class="text-xl font-semibold text-blue-600">Santo Cookware</a>
        <a href="/cart" class="relative">
            <span class="text-2xl">🛒</span>
            <span id="cart-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                {{ count(session('cart', [])) }}
            </span>
        </a>
    </div>
</nav>

<div class="container mx-auto px-4 py-8 grid md:grid-cols-3 gap-6 max-w-5xl">

    <!-- KIRI: ITEM -->
    <div class="md:col-span-2 space-y-4">
        <h1 class="text-xl font-semibold text-gray-800">Keranjang Belanja</h1>

        @php $cart = session('cart', []); @endphp

        <div id="cart-items">
        @forelse($cart as $id => $item)
        <div id="item-{{ $id }}" class="bg-white rounded-2xl border border-gray-100 p-4 flex gap-4">
            <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100' }}"
                 class="w-20 h-20 object-cover rounded-xl flex-shrink-0">
            <div class="flex-1">
                <h2 class="font-medium text-gray-800 text-sm">{{ $item['name'] }}</h2>
                <p class="text-blue-600 font-semibold mt-1">Rp {{ number_format($item['price']) }}</p>
                <div class="flex items-center gap-3 mt-2">
                    <button onclick="updateCart('{{ $id }}', 'minus')"
                        class="w-7 h-7 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium">−</button>
                    <span id="qty-{{ $id }}" class="text-sm font-medium">{{ $item['quantity'] }}</span>
                    <button onclick="updateCart('{{ $id }}', 'plus')"
                        class="w-7 h-7 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm font-medium">+</button>
                    <button onclick="removeCart('{{ $id }}')"
                        class="text-red-400 hover:text-red-600 text-xs ml-2">Hapus</button>
                </div>
            </div>
        </div>
        @empty
        <p id="empty-msg" class="text-gray-400 text-sm py-8 text-center">Keranjang kamu kosong.</p>
        @endforelse
        </div>
    </div>

    <!-- KANAN: CHECKOUT -->
    <div class="space-y-4">

        <!-- TOTAL -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4">
            @php
                $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
                $totalQty = collect($cart)->sum(fn($i) => $i['quantity']);
            @endphp
            <p class="text-sm text-gray-500">Total (<span id="total-qty">{{ $totalQty }}</span> produk)</p>
            <p id="total-price" class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($total) }}</p>
        </div>

        <!-- FORM -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 space-y-3">
            <h2 class="font-medium text-sm text-gray-700">Informasi Pelanggan</h2>
            <div class="flex gap-2">
                <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm w-24">
                    <option>Bapak</option><option>Ibu</option>
                </select>
                <input type="text" placeholder="Nama lengkap" class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <input type="text" placeholder="Nomor telepon" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <!-- PENGIRIMAN -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 space-y-3">
            <h2 class="font-medium text-sm text-gray-700">Pengiriman</h2>
            <div class="flex gap-4 text-sm">
                <label class="flex items-center gap-1"><input type="radio" name="ship" checked> Kirim ke rumah</label>
                <label class="flex items-center gap-1"><input type="radio" name="ship"> Ambil di toko</label>
            </div>
            <input type="text" placeholder="Kota / Kecamatan" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <input type="text" placeholder="Alamat lengkap" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <input type="text" placeholder="Catatan (opsional)" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>

        <!-- PEMBAYARAN -->
        <div class="bg-white rounded-2xl border border-gray-100 p-4 space-y-2">
            <h2 class="font-medium text-sm text-gray-700">Metode Pembayaran</h2>
            <div class="space-y-2 text-sm">
                <label class="flex items-center gap-2"><input type="radio" name="pay"> Transfer Bank</label>
                <label class="flex items-center gap-2"><input type="radio" name="pay"> Dana / E-Wallet</label>
                <label class="flex items-center gap-2"><input type="radio" name="pay"> COD</label>
            </div>
        </div>

        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-medium transition">
            Bayar Sekarang
        </button>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

async function updateCart(id, action) {
    const res = await fetch(`/cart/update/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ action })
    });
    const data = await res.json();
    if (!data.success) return;

    const cart = data.cart;

    if (!cart[id]) {
        document.getElementById(`item-${id}`)?.remove();
    } else {
        document.getElementById(`qty-${id}`).textContent = cart[id].quantity;
    }

    refreshTotal(cart);
}

async function removeCart(id) {
    const res = await fetch(`/cart/remove/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({})
    });
    const data = await res.json();
    if (!data.success) return;
    document.getElementById(`item-${id}`)?.remove();
    refreshTotal(data.cart);
}

function refreshTotal(cart) {
    const items = Object.values(cart);
    const qty = items.reduce((s, i) => s + i.quantity, 0);
    const total = items.reduce((s, i) => s + i.price * i.quantity, 0);
    document.getElementById('total-qty').textContent = qty;
    document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('cart-badge').textContent = qty;
    if (qty === 0) {
        document.getElementById('cart-items').innerHTML = '<p class="text-gray-400 text-sm py-8 text-center">Keranjang kamu kosong.</p>';
    }
}
</script>
</body>
</html>