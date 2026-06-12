@extends('admin.layout')
@section('page-title', 'Edit Pembayaran')

@section('content')
<div class="max-w-xl mx-auto">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.payments.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit: {{ $payment->label }}</h1>
            <p class="text-xs text-gray-400 mt-0.5">Perbarui detail metode pembayaran</p>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.payments.update', $payment) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">

            {{-- Label --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Metode</label>
                <input type="text" name="label" value="{{ old('label', $payment->label) }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 transition">
            </div>

            {{-- Nomor Rekening --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nomor Rekening / Nomor HP
                    <span class="text-gray-400 font-normal">(kosongkan untuk COD)</span>
                </label>
                <input type="text" name="account_number" value="{{ old('account_number', $payment->account_number) }}"
                       placeholder="Contoh: 1234-5678-9012"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 transition">
            </div>

            {{-- Nama Pemilik --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Pemilik Rekening</label>
                <input type="text" name="account_name" value="{{ old('account_name', $payment->account_name) }}"
                       placeholder="Nama a/n rekening"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-400 transition">
            </div>

            {{-- QR Image --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Gambar QR Code
                    <span class="text-gray-400 font-normal">(opsional, untuk QRIS)</span>
                </label>

                @if($payment->qr_image)
                <div class="mb-3">
                    <img src="{{ $payment->qr_image }}" class="w-32 h-32 object-contain border border-gray-100 rounded-xl p-2 bg-gray-50">
                    <p class="text-xs text-gray-400 mt-1">Gambar saat ini. Upload baru untuk mengganti.</p>
                </div>
                @endif

                <label for="qr-upload" class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-blue-50/20 transition">
                    <i class="fas fa-qrcode text-2xl text-gray-300 mb-1"></i>
                    <span id="qr-filename" class="text-xs text-gray-400">Klik untuk upload QR Code</span>
                </label>
                <input type="file" id="qr-upload" name="qr_image" accept="image/*" class="hidden"
                       onchange="document.getElementById('qr-filename').textContent = this.files[0]?.name ?? 'Klik untuk upload'">
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $payment->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 accent-blue-600">
                    <span class="text-sm text-gray-600">Aktifkan metode ini di checkout</span>
                </label>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.payments.index') }}"
                   class="flex-1 py-3 border border-gray-200 hover:bg-gray-50 text-gray-600 rounded-xl font-semibold text-sm text-center transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection