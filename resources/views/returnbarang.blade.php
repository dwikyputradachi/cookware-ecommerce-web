@extends ('layouts.app')
@section('title', 'Ketentuan Return Barang - Murazon')
@section('content')
<div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row gap-10">
    @include('partials._sidebar_about', ['active' => 'return'])
    <div class="flex-1 max-w-4xl">
        <h1 class="text-3xl font-bold mb-6">Ketentuan Return Barang dan Penggantian Uang</h1>
        <div class="bg-gray-50 p-8 rounded-3xl border border-gray-200">
            <p class="text-gray-700 mb-4">
                Kami memahami bahwa terkadang ada kebutuhan untuk melakukan return barang. Berikut adalah ketentuan return barang dan penggantian uang di Murazon:
            </p>
            <ul class="list-disc list-inside text-gray-700 mb-4">
                <li>Return barang dapat dilakukan dalam waktu 7 hari setelah penerimaan barang.</li>
                <li>Barang yang dikembalikan harus dalam kondisi asli, belum digunakan, dan dalam kemasan asli.</li>
                <li>Untuk proses return, silakan hubungi layanan pelanggan kami melalui email atau hotline yang tersedia.</li>
                <li>Setelah barang diterima dan diperiksa, penggantian uang akan diproses dalam waktu 5-7 hari kerja.</li>
            </ul>
            <p class="text-gray-700">
                Harap pastikan untuk menyimpan bukti pembelian dan kemasan asli untuk memudahkan proses return. Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi tim layanan pelanggan kami.
            </p>
        </div>
    </div>
</div>
@endsection
