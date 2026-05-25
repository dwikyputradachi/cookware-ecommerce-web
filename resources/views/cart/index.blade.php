@extends('layouts.app')

@section('title', 'Keranjang Belanja - Murazon')

@section('content')
@php
    // Cek apakah SEMUA produk di keranjang mendukung COD
    $canCod = collect($cart)->every(fn($i) => $i['is_cod_available'] ?? true);
@endphp

<div class="container mx-auto px-4 py-8 max-w-4xl"> 
    
    <div class="flex items-center gap-3 mb-8">
        <div class="p-3 bg-orange-50 rounded-2xl text-[#E1700F]">
            <i data-lucide="shopping-bag" class="w-6 h-6"></i>
        </div>
        <div>
            <h1 class="text-xl font-black text-gray-900 uppercase tracking-tight italic">Keranjang Belanja</h1>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total: <span id="total-qty-top">{{ collect($cart)->sum('quantity') }}</span> Produk Terpilih</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-8 items-start">
        
        {{-- LIST PRODUK (Kiri) --}}
        <div class="lg:col-span-3 space-y-3">
            <div id="cart-items" class="space-y-3">
                @forelse($cart as $id => $item)
                <div id="item-{{ $id }}" class="bg-white rounded-2xl border border-gray-100 p-4 flex gap-4 transition-all hover:border-orange-100 relative overflow-hidden">
                    
                    {{-- LABEL PROMO --}}
                    @if(isset($item['old_price']) && $item['old_price'] > $item['price'])
                        <div class="absolute top-0 right-0 bg-red-600 text-white text-[8px] font-black px-2 py-1 rounded-bl-xl uppercase tracking-tighter z-10">
                            Promo
                        </div>
                    @endif

                    <div class="relative shrink-0">
                        <img src="{{ str_contains($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']) }}"
                             class="w-20 h-20 object-contain bg-gray-50 rounded-xl border border-gray-50">
                        @if(!($item['is_cod_available'] ?? true))
                            <span class="absolute -top-1 -left-1 bg-black text-white text-[8px] font-black px-1.5 py-0.5 rounded uppercase">Non-COD</span>
                        @endif
                    </div>

                    <div class="flex-1 flex flex-col justify-between py-0.5">
                        <div class="flex justify-between items-start gap-2">
                            <h2 class="font-bold text-gray-900 text-sm leading-tight uppercase italic line-clamp-1">{{ $item['name'] }}</h2>
                            <button onclick="removeCart('{{ $id }}')" class="text-gray-300 hover:text-red-500 transition">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-end justify-between mt-auto">
                            <div>
                                @if(isset($item['old_price']) && $item['old_price'] > $item['price'])
                                    <p class="text-[10px] font-bold text-gray-400 line-through tracking-tighter mb-[-4px]">Rp {{ number_format($item['old_price']) }}</p>
                                @endif
                                <p class="text-sm font-black text-[#E1700F] italic">Rp {{ number_format($item['price']) }}</p>
                            </div>
                            
                            <div class="flex items-center gap-2 bg-gray-50 p-1 rounded-lg border border-gray-100 scale-90 origin-right">
                                <button onclick="updateCart('{{ $id }}', 'minus')" class="w-6 h-6 bg-white border border-gray-100 shadow-sm rounded-md flex items-center justify-center text-xs font-bold hover:bg-orange-50">−</button>
                                <span id="qty-{{ $id }}" class="text-[11px] font-black w-5 text-center">{{ $item['quantity'] }}</span>
                                <button onclick="updateCart('{{ $id }}', 'plus')" class="w-6 h-6 bg-white border border-gray-100 shadow-sm rounded-md flex items-center justify-center text-xs font-bold hover:bg-orange-50">+</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div id="empty-state" class="py-20 text-center border-2 border-dashed border-gray-100 rounded-[2rem]">
                    <i data-lucide="shopping-basket" class="w-12 h-12 text-gray-200 mx-auto mb-4"></i>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Wah, keranjang masih kosong</p>
                    <a href="/" class="mt-4 inline-block text-[10px] font-black uppercase tracking-tighter text-[#E1700F] border-b-2 border-[#E1700F]">Mulai Cari Barang →</a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- SIDEBAR CHECKOUT (Kanan) --}}
        <div class="lg:col-span-2 space-y-4 sticky top-24">
            <div class="bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm">
                <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <i data-lucide="truck" class="w-3 h-3 text-[#E1700F]"></i> Pengiriman & Bayar
                </h2>
                
                {{-- Form Data Diri --}}
                <div class="space-y-3 mb-6">
                    <div class="relative">
                        <input type="text" id="cust_name" placeholder="NAMA PENERIMA" class="w-full pl-4 pr-4 py-3 rounded-xl border border-gray-100 text-[11px] font-bold uppercase tracking-wider focus:ring-1 focus:ring-[#E1700F] outline-none bg-gray-50/50">
                    </div>
                    <div class="relative">
                        <input type="number" id="cust_phone" placeholder="NO. WHATSAPP (08...)" class="w-full pl-4 pr-4 py-3 rounded-xl border border-gray-100 text-[11px] font-bold uppercase tracking-wider focus:ring-1 focus:ring-[#E1700F] outline-none bg-gray-50/50">
                    </div>
                    <textarea id="cust_address" placeholder="ALAMAT LENGKAP" rows="2" class="w-full pl-4 pr-4 py-3 rounded-xl border border-gray-100 text-[11px] font-bold uppercase tracking-wider focus:ring-1 focus:ring-[#E1700F] outline-none bg-gray-50/50"></textarea>
                </div>

               {{-- Metode Pembayaran --}}
                <div class="mb-6">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-3 text-center">Pilih Pembayaran</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($payments as $pm)
                            @if($pm->payment_key !== 'cod' || $canCod)
                            <button onclick="selectPayment('{{ $pm->payment_key }}', this)"
                                    class="payment-opt p-3 rounded-xl border border-gray-100 hover:border-orange-200 transition text-center group">
                                @if($pm->qr_image)
                                    <img src="{{ $pm->qr_image }}" class="w-5 h-5 mx-auto mb-1 object-contain">
                                @else
                                    @php
                                        $icons = ['bca'=>'landmark','dana'=>'wallet','qris'=>'qr-code','cod'=>'package'];
                                    @endphp
                                    <i data-lucide="{{ $icons[$pm->payment_key] ?? 'credit-card' }}" class="w-4 h-4 mx-auto mb-1 text-gray-400"></i>
                                @endif
                                <span class="text-[9px] font-black uppercase text-gray-500">{{ $pm->label }}</span>
                            </button>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Detail Rekening & Upload Bukti --}}
                <div id="payment-info" class="hidden mb-6 space-y-3">
                    <div class="p-4 bg-gray-900 rounded-2xl text-white relative overflow-hidden transition-all duration-300">
                        <div class="relative z-10">
                            <p id="payment-title" class="text-[8px] font-bold text-orange-400 uppercase tracking-widest mb-1"></p>
                            <div class="flex items-center justify-between">
                                <p id="acc-number" class="text-base font-black tracking-tighter italic"></p>
                                <button onclick="copyToClipboard()" class="p-1.5 bg-white/10 rounded-lg hover:bg-white/20 transition">
                                    <i data-lucide="copy" class="w-3 h-3 text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div id="proof-container">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Upload Bukti Transfer</label>
                        <div class="relative group">
                            <input type="file" id="payment_proof" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="border-2 border-dashed border-gray-100 rounded-xl p-4 text-center group-hover:bg-gray-50 transition">
                                <i data-lucide="upload-cloud" class="w-5 h-5 text-gray-300 mx-auto mb-1"></i>
                                <p id="file-name" class="text-[9px] text-gray-400 font-bold truncate uppercase">Pilih Gambar Bukti</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-50 mb-6 flex justify-between items-end">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Harga</p>
                        <p id="total-price" class="text-xl font-black text-[#E1700F] italic">Rp {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity'])) }}</p>
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase"><span id="total-qty">{{ collect($cart)->sum('quantity') }}</span> item</p>
                </div>

                <button onclick="checkout()" class="w-full bg-[#E1700F] hover:bg-black text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest transition-all active:scale-95 shadow-lg shadow-orange-100 flex items-center justify-center gap-2">
                    Konfirmasi & Pesan <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL SYSTEM --}}
<div id="modal-overlay" class="fixed inset-0 z-[200] flex items-center justify-center p-4 hidden" style="background:rgba(0,0,0,0.5);">
    <div id="modal-box" class="bg-white rounded-[1.5rem] w-full max-w-sm overflow-hidden shadow-2xl transform transition-all">
        <div class="px-6 pt-6 pb-0 flex flex-col items-center text-center">
            <div id="modal-icon-wrap" class="w-14 h-14 rounded-full flex items-center justify-center mb-4">
                <svg id="modal-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></svg>
            </div>
            <p id="modal-title" class="font-bold text-gray-900 text-base mb-1"></p>
            <p id="modal-msg" class="text-xs text-gray-400 leading-relaxed mb-5"></p>
            <ul id="modal-fields" class="hidden w-full text-left mb-4 space-y-2"></ul>
        </div>
        <div id="modal-buttons" class="px-6 pb-6 flex gap-3"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let selectedMethod = '';

const paymentDetails = {
    @foreach($payments as $pm)
    '{{ $pm->payment_key }}': {
        title: @json($pm->label),
        number: @json($pm->account_number ?? 'Bayar di tempat'),
        name: @json($pm->account_name ?? 'Murazon Cookware'),
        qr: @json($pm->qr_image ?? ''),
    },
    @endforeach
};

// MODAL ENGINE
const overlay  = document.getElementById('modal-overlay');
const iconWrap = document.getElementById('modal-icon-wrap');
const iconEl   = document.getElementById('modal-icon');
const titleEl  = document.getElementById('modal-title');
const msgEl    = document.getElementById('modal-msg');
const fieldsEl = document.getElementById('modal-fields');
const btnsEl   = document.getElementById('modal-buttons');

const ICONS = {
    warning: `<path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>`,
    danger:  `<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>`,
    upload:  `<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>`,
    trash:   `<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/>`,
    card:    `<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>`,
};

function showModal({ type = 'warning', title, msg, fields = [], buttons }) {
    const isRed = type === 'danger';
    const color  = isRed ? '#dc2626' : '#E1700F';
    const bgIcon = isRed ? '#fee2e2'  : '#ffedd5';
    iconWrap.style.background = bgIcon;
    iconEl.setAttribute('stroke', color);
    iconEl.innerHTML = ICONS[type] ?? ICONS.warning;
    titleEl.textContent = title;
    msgEl.textContent   = msg;
    if (fields.length) {
        fieldsEl.classList.remove('hidden');
        fieldsEl.innerHTML = fields.map(f =>
            `<li style="display:flex;align-items:center;gap:8px;font-size:12px;color:#6b7280;">
                <span style="width:6px;height:6px;border-radius:50%;background:${f.ok ? '#16a34a' : color};flex-shrink:0;"></span>
                ${f.label}${f.ok ? ' <span style="color:#16a34a;font-size:11px;">✓ terisi</span>' : ''}
             </li>`
        ).join('');
    } else {
        fieldsEl.classList.add('hidden');
    }
    btnsEl.innerHTML = '';
    buttons.forEach(b => {
        const btn = document.createElement('button');
        btn.textContent = b.label;
        btn.style.cssText = `flex:1;padding:11px;border-radius:10px;font-size:12px;font-weight:700;cursor:pointer;border:none;
            background:${b.primary ? color : 'transparent'};
            color:${b.primary ? '#fff' : '#6b7280'};
            border:${b.primary ? 'none' : '1px solid #e5e7eb'};`;
        btn.onclick = () => { closeModal(); b.action?.(); };
        btnsEl.appendChild(btn);
    });
    overlay.classList.remove('hidden');
}

function closeModal() { overlay.classList.add('hidden'); }

// PAYMENT SELECT
function selectPayment(method, el) {
    selectedMethod = method;

    document.querySelectorAll('.payment-opt').forEach(b =>
        b.classList.remove('selected', 'border-[#E1700F]', 'bg-orange-50/50')
    );

    el.classList.add('selected', 'border-[#E1700F]', 'bg-orange-50/50');

    const info = document.getElementById('payment-info');
    const detail = paymentDetails[method];

    if (!detail) {
        showModal({
            type: 'danger',
            title: 'Metode tidak ditemukan',
            msg: 'Data metode pembayaran belum terbaca.',
            buttons: [{ label: 'Tutup', primary: true }]
        });
        return;
    }

    document.getElementById('payment-title').textContent = detail.title;
    document.getElementById('acc-number').textContent = detail.number;

    let nameEl = document.getElementById('account-name-display');
    if (!nameEl) {
        nameEl = document.createElement('p');
        nameEl.id = 'account-name-display';
        nameEl.className = 'text-[8px] opacity-50 uppercase mt-1';
        document.getElementById('acc-number').parentElement.parentElement.appendChild(nameEl);
    }
    nameEl.textContent = 'A/N ' + detail.name;

    let qrEl = document.getElementById('qr-display');
    if (!qrEl) {
        qrEl = document.createElement('div');
        qrEl.id = 'qr-display';
        document.getElementById('acc-number').parentElement.parentElement.appendChild(qrEl);
    }

    qrEl.innerHTML = detail.qr
    ? `
        <div class="mt-3">
            <p class="text-[10px] font-bold uppercase text-white/70 mb-2 text-center">Scan QR</p>
            <div class="bg-white rounded-2xl p-3 border border-gray-200 shadow-sm flex justify-center">
                <img src="${detail.qr}" class="w-40 h-40 object-contain">
            </div>
        </div>
      `
    : '';
    info.classList.remove('hidden');
    document.getElementById('proof-container').classList.toggle('hidden', method === 'cod');

    window.lucide?.createIcons?.();
}

document.getElementById('payment_proof').addEventListener('change', e => {
    document.getElementById('file-name').textContent = e.target.files[0]?.name || 'Pilih Gambar Bukti';
});

function copyToClipboard() {
    navigator.clipboard.writeText(document.getElementById('acc-number').textContent);
    alert('Nomor disalin!');
}

// CART ACTIONS
async function updateCart(id, action) {
    const currentQty = parseInt(document.getElementById(`qty-${id}`).textContent);
    if (action === 'minus' && currentQty <= 1) {
        showModal({
            type: 'trash', title: 'Hapus produk ini?', msg: 'Jumlah sudah minimum. Hapus produk?',
            buttons: [
                { label: 'Batal', primary: false },
                { label: 'Hapus', primary: true, action: async () => {
                    const res = await fetch(`/cart/remove/${id}`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }});
                    const data = await res.json();
                    if (data.success) { document.getElementById(`item-${id}`)?.remove(); refreshTotal(data.cart); }
                }},
            ],
        });
        return;
    }
    const res = await fetch(`/cart/update/${id}`, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ action })});
    const data = await res.json();
    if (data.success) {
        if (!data.cart[id]) { document.getElementById(`item-${id}`)?.remove(); } 
        else { document.getElementById(`qty-${id}`).textContent = data.cart[id].quantity; }
        refreshTotal(data.cart);
    }
}

async function removeCart(id) {
    showModal({
        type: 'trash', title: 'Hapus produk?', msg: 'Produk akan dihapus dari keranjang.',
        buttons: [
            { label: 'Batal', primary: false },
            { label: 'Hapus', primary: true, action: async () => {
                const res = await fetch(`/cart/remove/${id}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf }});
                const data = await res.json();
                if (data.success) { document.getElementById(`item-${id}`)?.remove(); refreshTotal(data.cart); }
            }},
        ],
    });
}

function refreshTotal(cart) {
    const items = Object.values(cart);
    const qty   = items.reduce((s, i) => s + (i.quantity ?? 0), 0);
    const total = items.reduce((s, i) => s + (i.price * (i.quantity ?? 0)), 0);
    document.getElementById('total-qty').textContent = qty;
    document.getElementById('total-qty-top').textContent = qty;
    document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
    if (qty === 0) location.reload();
}

// CHECKOUT
async function checkout() {
    const name    = document.getElementById('cust_name').value.trim();
    const phone   = document.getElementById('cust_phone').value.trim();
    const address = document.getElementById('cust_address').value.trim();
    const proofFile = document.getElementById('payment_proof').files[0];

    if (!name || !phone || !address) {
        showModal({
            type: 'warning', title: 'Data belum lengkap', msg: 'Lengkapi data pengiriman.',
            fields: [{ label: 'Nama', ok: !!name }, { label: 'WA', ok: !!phone }, { label: 'Alamat', ok: !!address }],
            buttons: [{ label: 'Isi sekarang', primary: true }],
        });
        return;
    }
    if (!selectedMethod) {
        showModal({ type: 'card', title: 'Pilih pembayaran', msg: 'Pilih metode bayar.', buttons: [{ label: 'Oke', primary: true }]});
        return;
    }
    if (selectedMethod !== 'cod' && !proofFile) {
        showModal({ type: 'upload', title: 'Upload bukti', msg: 'Wajib upload bukti transfer.', buttons: [{ label: 'Upload', primary: true }]});
        return;
    }

    const totalPriceRaw = document.getElementById('total-price').textContent.replace(/[^0-9]/g, '');
    const formData = new FormData();
    formData.append('name', name);
    formData.append('phone', phone);
    formData.append('address', address);
    formData.append('payment_method', selectedMethod);
    formData.append('total_price', totalPriceRaw);
    if (proofFile) formData.append('payment_proof', proofFile);

    try {
        const response = await fetch('/checkout', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf }, body: formData });
        const result = await response.json();
        if (result.success) {
    const isPaid = result.data_server.payment_method !== 'cod';
    const msg = 
        `PESANAN BARU - MURAZON COOKWARE\n` +
        `Order ID: #${result.order_id}\n` +
        `━━━━━━━━━━━━━━━━━━━━━━━━\n` +
        `DETAIL PELANGGAN\n` +
        `Nama    : ${result.data_server.name}\n` +
        `No. WA  : ${result.data_server.phone}\n` +
        `Alamat  : ${result.data_server.address}\n\n` +
        `🛒 DETAIL PRODUK\n` +
        `${result.items_string}\n` +
        `━━━━━━━━━━━━━━━━━━━━━━━━\n` +
        `💰 TOTAL  : Rp ${result.data_server.total_price}\n` +
        `💳 BAYAR  : ${result.data_server.payment_method.toUpperCase()}\n` +
        `📋 STATUS : ${isPaid ? 'Sudah Bayar (Menunggu Verifikasi)' : 'COD - Bayar di Tempat'}\n` +
        `━━━━━━━━━━━━━━━━━━━━━━━━\n` +
        `Terima kasih sudah berbelanja di Murazon! 🙏`;

    window.open(`https://wa.me/6282285455631?text=${encodeURIComponent(msg)}`, '_blank');
    location.href = '/';
}
    } catch (e) {
        showModal({ type: 'danger', title: 'Gagal', msg: 'Terjadi kesalahan sistem.', buttons: [{ label: 'Tutup', primary: true }]});
    }
}

</script>
@endpush