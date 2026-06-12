<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('payment_settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();   // bca, dana, qris, cod
        $table->string('label');           // Transfer BCA
        $table->string('account_number')->nullable(); // nomor rekening
        $table->string('account_name')->nullable();   // nama pemilik
        $table->string('qr_image')->nullable();       // URL gambar QR
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
