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
    Schema::create('admin_otps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
        $table->string('otp_code', 6);
        $table->timestamp('expires_at');
        $table->boolean('is_used')->default(false);
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_otps');
    }
};
