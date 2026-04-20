<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('banners', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable();
        $table->string('image'); // Path gambar banner
        $table->string('link')->nullable(); // Biar kalau diklik bisa lari ke produk tertentu
        $table->boolean('is_active')->default(true);
        $table->integer('order')->default(0); // Buat nentuin urutan banner
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
