<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('name');
            }

            if (!Schema::hasColumn('products', 'is_promo')) {
                $table->boolean('is_promo')->default(false)->after('is_cod_available');
            }

            if (!Schema::hasColumn('products', 'discount_price')) {
                $table->decimal('discount_price', 12, 2)->nullable()->after('price');
            }

            if (!Schema::hasColumn('products', 'total_sold')) {
                $table->integer('total_sold')->default(0)->after('stock');
            }

            if (!Schema::hasColumn('products', 'rating')) {
                $table->decimal('rating', 3, 2)->default(0)->after('total_sold');
            }

            if (!Schema::hasColumn('products', 'video_url')) {
                $table->string('video_url')->nullable()->after('image');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->string('shipping_address')->nullable()->after('customer_address');
            }

            if (!Schema::hasColumn('orders', 'payment_proof')) {
                $table->text('payment_proof')->nullable()->after('status');
            }
        });

        DB::statement("ALTER TABLE orders MODIFY payment_method VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE orders MODIFY status VARCHAR(50) NOT NULL DEFAULT 'pending'");

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->integer('quantity');
                $table->decimal('price', 12, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Jangan drop kolom production sembarangan.
    }
};