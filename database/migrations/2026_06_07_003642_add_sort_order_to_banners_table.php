<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('banners', 'sort_order')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_active')->index();
            });
        }

        if (Schema::hasColumn('banners', 'order')) {
            DB::statement('UPDATE banners SET sort_order = `order` WHERE sort_order = 0');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('banners', 'sort_order')) {
            Schema::table('banners', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};