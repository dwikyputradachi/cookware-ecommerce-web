@extends('layouts.app')

@section('title', 'Panduan Belanja - Murazon')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-10 flex flex-col lg:flex-row gap-8">

        @include('partials._sidebar_about', ['active' => 'panduan'])

        <div class="flex-1 max-w-4xl">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-[#6B3005] via-[#A1500A] to-[#E1700F] rounded-3xl p-8 md:p-10 text-white shadow-xl mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-52 h-52 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <p class="uppercase tracking-[0.3em] text-orange-200 text-xs font-bold mb-3">
                        Murazon Shopping Guide
                    </p>

                    <h1 class="text-3xl md:text-5xl font-black leading-tight mb-4">
                        Panduan Belanja Online
                    </h1>

                    <p class="text-orange-100 max-w-2xl text-sm md:text-base leading-relaxed">
                        Ikuti langkah berikut untuk melakukan pembelian produk di Murazon
                        mulai dari memilih produk hingga memberikan ulasan setelah pesanan selesai.
                    </p>
                </div>
            </div>

            {{-- Step Guide --}}
            <div class="space-y-6">

                {{-- Step 1 --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#E1700F] flex items-center justify-center font-black text-lg">
                            1
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                Pilih Produk
                            </h2>

                            <p class="text-gray-600 leading-relaxed">
                                Jelajahi produk cookware yang tersedia di halaman utama.
                                Anda dapat menggunakan fitur filter kategori, harga,
                                rating, dan stok untuk mempermudah pencarian produk.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#E1700F] flex items-center justify-center font-black text-lg">
                            2
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                Tambahkan ke Keranjang
                            </h2>

                            <p class="text-gray-600 leading-relaxed">
                                Setelah menemukan produk yang diinginkan,
                                klik tombol <span class="font-semibold text-[#E1700F]">Tambah ke Keranjang</span>.
                                Anda dapat menambahkan beberapa produk sekaligus sebelum checkout.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#E1700F] flex items-center justify-center font-black text-lg">
                            3
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                Checkout Pesanan
                            </h2>

                            <p class="text-gray-600 leading-relaxed">
                                Buka halaman keranjang lalu lakukan checkout dengan mengisi:
                            </p>

                            <ul class="mt-3 space-y-2 text-gray-600">
                                <li>• Nama lengkap</li>
                                <li>• Nomor WhatsApp aktif</li>
                                <li>• Alamat pengiriman lengkap</li>
                                <li>• Metode pembayaran</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Step 4 --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#E1700F] flex items-center justify-center font-black text-lg">
                            4
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                Lakukan Pembayaran
                            </h2>

                            <p class="text-gray-600 leading-relaxed">
                                Setelah checkout selesai, lakukan pembayaran sesuai total pesanan.
                                Kemudian upload bukti transfer agar admin dapat melakukan verifikasi pesanan.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Step 5 --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#E1700F] flex items-center justify-center font-black text-lg">
                            5
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                Verifikasi Pesanan
                            </h2>

                            <p class="text-gray-600 leading-relaxed">
                                Admin akan memeriksa bukti pembayaran Anda.
                                Jika pembayaran valid, pesanan akan disetujui dan diproses.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Step 6 --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#E1700F] flex items-center justify-center font-black text-lg">
                            6
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                Cek Status Pesanan
                            </h2>

                            <p class="text-gray-600 leading-relaxed">
                                Masuk ke halaman <span class="font-semibold text-[#E1700F]">Pesanan</span>
                                pada navbar atas, lalu masukkan nomor WhatsApp yang digunakan saat checkout
                                untuk melihat status pesanan Anda.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Step 7 --}}
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-[#E1700F] flex items-center justify-center font-black text-lg">
                            7
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-800 mb-2">
                                Berikan Rating & Ulasan
                            </h2>

                            <p class="text-gray-600 leading-relaxed">
                                Setelah pesanan berhasil diverifikasi admin,
                                tombol <span class="font-semibold text-[#E1700F]">Beri Ulasan</span>
                                akan muncul. Anda dapat memberikan rating bintang
                                dan ulasan terhadap produk yang telah dibeli.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection