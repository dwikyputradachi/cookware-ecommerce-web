<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menambah kolom bukti pembayaran
            $table->string('payment_proof')->nullable()->after('status');

            // Memperlebar kolom yang sudah ada agar tidak error "Data truncated"
            $table->string('payment_method', 50)->change();
            $table->string('status', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
            // Tidak perlu me-revert change() jika tidak diperlukan
        });
    }
};
