<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah kolom user_id (nullable, foreign key)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('id');
            
            // Tambah kolom untuk alamat pengiriman
            $table->text('shipping_address')->nullable()->after('customer_address');
            
            // Tambah kolom bukti pembayaran
            $table->string('payment_proof')->nullable()->after('status');
            
            // Update enum status
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['user_id']);
            $table->dropColumn(['user_id', 'shipping_address', 'payment_proof']);
        });
    }
};
