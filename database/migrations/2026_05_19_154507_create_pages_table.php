<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('pages')) {
            Schema::create('pages', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->unique();
                $table->string('title');
                $table->longText('content');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (DB::table('pages')->count() === 0) {
            DB::table('pages')->insert([
                [
                    'slug' => 'about-us',
                    'title' => 'Tentang Murazon',
                    'content' => '<div class="rounded-3xl overflow-hidden mb-8 shadow-lg"><img src="/img/dummy-about.jpg" class="w-full h-80 object-cover bg-gray-200" alt="About Murazon"></div><p class="text-gray-600 leading-relaxed mb-4">Murazon adalah pusat perlengkapan dapur premium yang berfokus pada kualitas dan inovasi. Kami hadir untuk memenuhi kebutuhan peralatan masak modern keluarga Indonesia.</p><p class="text-gray-600 leading-relaxed">Berdiri sejak 2026, kami berkomitmen memberikan produk original dengan pelayanan purna jual terbaik melalui jaringan distribusi yang luas.</p>',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'garansi',
                    'title' => 'Kebijakan Garansi',
                    'content' => '<div class="space-y-6"><div class="p-6 bg-orange-50 rounded-2xl border border-orange-100"><h3 class="font-bold text-gray-900 mb-2">Syarat & Ketentuan</h3><ul class="list-disc ml-5 text-gray-600 space-y-2"><li>Garansi berlaku 12 bulan untuk cacat produksi.</li><li>Wajib menyertakan video unboxing saat klaim.</li><li>Kerusakan akibat kelalaian pemakaian tidak ditanggung.</li></ul></div></div>',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'return',
                    'title' => 'Ketentuan Return Barang dan Penggantian Uang',
                    'content' => '<div class="bg-gray-50 p-8 rounded-3xl border border-gray-200"><p class="text-gray-700 mb-4">Kami memahami bahwa terkadang ada kebutuhan untuk melakukan return barang. Berikut adalah ketentuan return barang dan penggantian uang di Murazon:</p><ul class="list-disc list-inside text-gray-700 mb-4"><li>Return barang dapat dilakukan dalam waktu 7 hari setelah penerimaan barang.</li><li>Barang yang dikembalikan harus dalam kondisi asli, belum digunakan, dan dalam kemasan asli.</li><li>Untuk proses return, silakan hubungi layanan pelanggan kami melalui email atau hotline yang tersedia.</li><li>Setelah barang diterima dan diperiksa, penggantian uang akan diproses dalam waktu 5-7 hari kerja.</li></ul><p class="text-gray-700">Harap pastikan untuk menyimpan bukti pembelian dan kemasan asli untuk memudahkan proses return. Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi tim layanan pelanggan kami.</p></div>',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'panduan',
                    'title' => 'Panduan Belanja',
                    'content' => '<p class="text-gray-700">Temukan cara mudah berbelanja di Murazon, mulai dari mencari produk hingga konfirmasi pesanan. Pastikan selalu memeriksa detail produk dan estimasi pengiriman sebelum checkout.</p>',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slug' => 'penipuan',
                    'title' => 'Waspada Penipuan',
                    'content' => '<p class="text-gray-700">Murazon hanya berkomunikasi melalui kanal resmi yang tercantum di website. Hindari melakukan pembayaran di luar sistem dan selalu cek kembali email atau nomor yang Anda terima.</p>',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
