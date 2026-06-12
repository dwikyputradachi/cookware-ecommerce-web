<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE products MODIFY price BIGINT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY discount_price BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE orders MODIFY total_price BIGINT UNSIGNED NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE order_items MODIFY price BIGINT UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE products MODIFY price DECIMAL(10,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE products MODIFY discount_price DECIMAL(10,2) NULL');
        DB::statement('ALTER TABLE orders MODIFY total_price DECIMAL(10,2) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE order_items MODIFY price DECIMAL(10,2) NOT NULL DEFAULT 0');
    }
};