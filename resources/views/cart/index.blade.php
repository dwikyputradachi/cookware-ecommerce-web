@extends('layouts.app')

@section('title', 'Keranjang - Santo Cookware')

@section('content')
<style>
    :root {
        --color-primary: #6B3005;    /* Cokelat Tua */
        --color-secondary: #E1700F;  /* Oranye Murazon */
        --color-accent-soft: #fff7ed; 
    }

    /* Override utility classes untuk warna Murazon */
    .bg-murazon-primary { background-color: var(--color-primary); }
    .bg-murazon-secondary { background-color: var(--color-secondary); }
    .text-murazon-primary { color: var(--color-primary); }
    .text-murazon-secondary { color: var(--color-secondary); }
    .border-murazon-primary { border-color: var(--color-primary); }
</style>

<div class="container mx-auto px-4 py-8 max-w-5xl">
    <div class="grid md:grid-cols-3 gap-8">
        
        {{-- List Produk --}}
        <div class="md:col-span-2 space-y-4">
            <div class="flex items-center justify-between mb-2">
                <h1 class="text-2xl font-bold text-[#1E293B] tracking-tight">Keranjang Belanja</h1>
                <span class="text-sm text-orange-800 bg-orange-50 px-3 py-1 rounded-full font-medium border border-orange-100">
                    <span id="total-qty-top">{{ collect($cart)->sum('quantity') }}</span> Produk
                </span>
            </div>

            <div id="cart-items" class="space-y-4">
                @forelse($cart as $id => $item)
                <div id="item-{{ $id }}" class="bg-white rounded-3xl border border-gray-100 p-5 flex gap-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="relative">
                        <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100' }}"
                             class="w-24 h-24 object-cover rounded-2xl shadow-inner border border-gray-50">
                        @if(!($item['is_cod_available'] ?? true))
                            <span class="absolute -top-2 -left-2 bg-gray-800 text-white text-[9px] font-bold px-2 py-1 rounded-lg">NON-COD</span>
                        @endif
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h2 class="font-bold text-[#6B3005] text-base leading-tight">{{ $item['name'] }}</h2>
                            <p class="text-[#E1700F] font-extrabold mt-1">Rp {{ number_format($item['price']) }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center gap-3 bg-orange-50/50 p-1 rounded-xl border border-orange-100">
                                <button onclick="updateCart('{{ $id }}', 'minus')"
                                    class="w-8 h-8 bg-white hover:bg-orange-100 shadow-sm rounded-lg flex items-center justify-center transition active:scale-90 font-bold text-[#6B3005]">−</button>
                                <span id="qty-{{ $id }}" class="text-sm font-bold w-6 text-center text-[#6B3005]">{{ $item['quantity'] }}</span>
                                <button onclick="updateCart('{{ $id }}', 'plus')"
                                    class="w-8 h-8 bg-white hover:bg-orange-100 shadow-sm rounded-lg flex items-center justify-center transition active:scale-90 font-bold text-[#6B3005]">+</button>
                            </div>

                            <button onclick="removeCart('{{ $id }}')"
                                class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div id="empty-state" class="bg-white rounded-[2.5rem] p-16 text-center border border-dashed border-orange-200">
                    <div class="bg-orange-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="shopping-basket" class="w-10 h-10 text-orange-300"></i>
                    </div>
                    <h3 class="text-[#6B3005] font-bold text-lg">Wah, keranjang kosong!</h3>
                    <p class="text-gray-400 text-sm mt-1">Yuk, isi dengan alat masak berkualitas Murazon.</p>
                    <a href="/" class="mt-6 inline-flex items-center gap-2 bg-[#E1700F] text-white px-8 py-3 rounded-2xl font-bold shadow-lg shadow-orange-200 hover:bg-[#6B3005] transition">
                        Mulai Belanja <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar Checkout --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-24">
                <h2 class="text-lg font-bold text-[#6B3005] mb-6 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-5 h-5 text-[#E1700F]"></i> Ringkasan & Bayar
                </h2>
                
                <div class="flex justify-between text-sm text-gray-500 mb-6">
                    <span>Total Harga (<span id="total-qty">{{ collect($cart)->sum('quantity') }}</span> barang)</span>
                    <span id="total-price" class="font-bold text-[#6B3005] text-base">Rp {{ number_format(collect($cart)->sum(fn($i) => $i['price'] * $i['quantity'])) }}</span>
                </div>

                <div class="space-y-3 mb-6">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Data Pengiriman</p>
                    <input type="text" id="cust_name" placeholder="Nama Lengkap" class="w-full p-3 rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-[#E1700F] outline-none transition-all bg-gray-50/30">
                    <input type="number" id="cust_phone" placeholder="Nomor WhatsApp (08xxx)" class="w-full p-3 rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-[#E1700F] outline-none transition-all bg-gray-50/30">
                    <textarea id="cust_address" placeholder="Alamat Lengkap" class="w-full p-3 rounded-xl border border-gray-100 text-sm focus:ring-2 focus:ring-[#E1700F] outline-none transition-all bg-gray-50/30"></textarea>
                </div>

                <div class="pt-4 border-t border-gray-100 mb-6">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Pilih Metode Pembayaran</p>
                    <div class="grid grid-cols-1 gap-2">
                        <button onclick="selectPayment('bank')" class="payment-opt flex items-center justify-between p-3 rounded-2xl border-2 border-gray-50 hover:border-orange-200 transition text-left group">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition"><i data-lucide="landmark" class="w-5 h-5 text-[#6B3005]"></i></div>
                                <span class="text-sm font-bold text-gray-700">Transfer Bank</span>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                        </button>

                        <button onclick="selectPayment('ewallet')" class="payment-opt flex items-center justify-between p-3 rounded-2xl border-2 border-gray-50 hover:border-orange-200 transition text-left group">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition"><i data-lucide="smartphone" class="w-5 h-5 text-[#6B3005]"></i></div>
                                <span class="text-sm font-bold text-gray-700">DANA / E-Wallet</span>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                        </button>

                        <button onclick="selectPayment('qris')" class="payment-opt flex items-center justify-between p-3 rounded-2xl border-2 border-gray-50 hover:border-red-200 transition text-left group">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-50 rounded-xl group-hover:bg-orange-100 transition"><i data-lucide="qr-code" class="w-5 h-5 text-[#6B3005]"></i></div>
                                <span class="text-sm font-bold text-gray-700">QRIS / VA Manual</span>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                        </button>

                        @php $canCod = collect($cart)->every(fn($i) => ($i['is_cod_available'] ?? true) == true); @endphp
                        <div id="cod-container" class="{{ ($canCod && count($cart) > 0) ? '' : 'hidden' }}">
                            <button onclick="selectPayment('cod')" class="payment-opt flex items-center justify-between p-3 rounded-2xl border-2 border-gray-50 hover:border-orange-200 transition text-left group w-full">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition"><i data-lucide="truck" class="w-5 h-5 text-[#6B3005]"></i></div>
                                    <span class="text-sm font-bold text-gray-700">Bayar di Tempat (COD)</span>
                                </div>
                                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="payment-info" class="hidden mb-6 p-4 bg-orange-50 rounded-2xl border border-orange-100">
                    <p id="payment-title" class="text-[10px] font-bold text-[#E1700F] uppercase mb-2"></p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p id="acc-number" class="text-lg font-black text-[#6B3005] tracking-wider"></p>
                            <p class="text-[10px] text-orange-400 font-medium italic uppercase tracking-tighter">A/N MURAZON COOKWARE</p>
                        </div>
                        <button onclick="copyAcc()" class="p-2 bg-white rounded-lg shadow-sm text-[#E1700F] active:scale-90 transition border border-orange-100">
                            <i data-lucide="copy" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div id="proof-container" class="mt-4 pt-4 border-t border-orange-200/50">
                        <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest mb-2">Upload Bukti Transfer</p>
                        <div class="relative group">
                            <input type="file" id="payment_proof" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="border-2 border-dashed border-orange-200 rounded-xl p-3 text-center group-hover:bg-orange-100/50 transition bg-white/50">
                                <i data-lucide="upload-cloud" class="w-5 h-5 text-orange-400 mx-auto mb-1"></i>
                                <p id="file-name" class="text-[10px] text-orange-500 font-bold truncate">Klik untuk pilih gambar</p>
                            </div>
                        </div>
                    </div>
                </div>

                <button onclick="checkout()" class="w-full bg-[#E1700F] hover:bg-[#6B3005] text-white py-4 rounded-3xl font-bold shadow-xl shadow-orange-100 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    Konfirmasi & Pesan via WA <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let selectedMethod = '';

const paymentDetails = {
    bank: { title: 'REKENING BANK BRI', number: '1234-5678-9012' },
    ewallet: { title: 'NOMOR DANA / OVO', number: '0822-8545-5631' },
    qris: { title: 'VA / QRIS MANUAL', number: 'VA-SANTO-9921' },
    cod: { title: 'METODE COD', number: 'BAYAR SAAT TIBA' }
};

function selectPayment(method) {
    selectedMethod = method;
    const infoBox = document.getElementById('payment-info');
    const proofBox = document.getElementById('proof-container');
    const titleEl = document.getElementById('payment-title');
    const numEl = document.getElementById('acc-number');
    
    // Highlight UI
    document.querySelectorAll('.payment-opt').forEach(el => el.classList.remove('border-blue-500', 'bg-blue-50'));
    event.currentTarget.classList.add('border-blue-500', 'bg-blue-50');

    // Update Info
    titleEl.textContent = paymentDetails[method].title;
    numEl.textContent = paymentDetails[method].number;
    infoBox.classList.remove('hidden');

    // Sembunyikan upload jika COD
    if(method === 'cod') proofBox.classList.add('hidden'); 
    else proofBox.classList.remove('hidden');
    
    window.refreshIcons();
}

// Listener Nama File
document.getElementById('payment_proof').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || "Klik untuk pilih gambar";
    document.getElementById('file-name').textContent = fileName;
});

function copyAcc() {
    const num = document.getElementById('acc-number').textContent;
    navigator.clipboard.writeText(num);
    alert('Nomor berhasil disalin!');
}

async function updateCart(id, action) {
    const res = await fetch(`/cart/update/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ action })
    });
    const data = await res.json();
    if (data.success) {
        if (!data.cart[id]) {
            document.getElementById(`item-${id}`).remove();
        } else {
            document.getElementById(`qty-${id}`).textContent = data.cart[id].quantity;
        }
        refreshTotal(data.cart);
    }
}

async function removeCart(id) {
    if(!confirm('Hapus dari keranjang?')) return;
    const res = await fetch(`/cart/remove/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({})
    });
    const data = await res.json();
    if (data.success) {
        document.getElementById(`item-${id}`).remove();
        refreshTotal(data.cart);
    }
}

function refreshTotal(cart) {
    const items = Object.values(cart);
    const qty = items.reduce((s, i) => s + i.quantity, 0);
    const total = items.reduce((s, i) => s + i.price * i.quantity, 0);
    
    const navBadge = document.getElementById('cart-badge');
    if (navBadge) {
        navBadge.style.transform = 'scale(1.3)';
        setTimeout(() => {
            navBadge.style.transform = 'scale(1)';
        }, 200);
        navBadge.textContent = qty;
        if (qty > 0) {
            navBadge.classList.remove('hidden');
        } else {
            navBadge.classList.add('hidden');
        }
    }
    // --------------------------------------------

    const canCod = items.length > 0 && items.every(i => (i.is_cod_available == true || i.is_cod_available == 1));
    const codContainer = document.getElementById('cod-container');
    if(canCod) codContainer.classList.remove('hidden'); else codContainer.classList.add('hidden');

    document.getElementById('total-qty').textContent = qty;
    document.getElementById('total-qty-top').textContent = qty;
    document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
    
    if (qty === 0) location.reload();
    window.refreshIcons();
}

async function checkout() {
    const name = document.getElementById('cust_name').value;
    const phone = document.getElementById('cust_phone').value;
    const address = document.getElementById('cust_address').value;
    const proofFile = document.getElementById('payment_proof').files[0];
    
    // Ambil harga asli dari teks untuk dikirim ke server (sebagai formalitas)
    const totalPriceRaw = document.getElementById('total-price').textContent.replace(/[^0-9]/g, ''); 

    if (!name || !phone || !address || !selectedMethod) {
        return alert('Harap isi data diri dan pilih metode pembayaran!');
    }

    const formData = new FormData();
    formData.append('name', name);
    formData.append('phone', phone);
    formData.append('address', address);
    formData.append('payment_method', selectedMethod);
    formData.append('total_price', totalPriceRaw);
    if(proofFile) formData.append('payment_proof', proofFile);

    try {
        const response = await fetch('/checkout', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            /* PENTING: Kita gunakan data dari 'result' (Server), 
               bukan dari variabel local di atas agar tidak bisa dimanipulasi.
            */
            const msg = `KONFIRMASI PESANAN BARU - SANTO COOKWARE
Order ID: #${result.order_id}
------------------------------------------
DETAIL PELANGGAN
Nama: ${result.data_server.name}
No. WA: ${result.data_server.phone}
Alamat: ${result.data_server.address}

DETAIL PRODUK
${result.items_string}

TOTAL PEMBAYARAN
Rp ${result.data_server.total_price}

METODE PEMBAYARAN
${result.data_server.payment_method.toUpperCase()}

STATUS
${result.data_server.status_text}
------------------------------------------
Pesanan telah tercatat di sistem Admin.`;

            window.open(`https://wa.me/6282285455631?text=${encodeURIComponent(msg)}`, '_blank');
            location.href = '/'; 
        } else {
            alert('Error: ' + (result.error || 'Gagal simpan data'));
        }
    } catch (e) {
        alert('Gagal membuat pesanan. Cek koneksi.');
    }
}
</script>
@endpush