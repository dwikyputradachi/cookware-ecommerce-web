<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'authenticator_secret')) {
                $table->text('authenticator_secret')->nullable()->after('password');
            }

            if (!Schema::hasColumn('admins', 'authenticator_enabled_at')) {
                $table->timestamp('authenticator_enabled_at')->nullable()->after('authenticator_secret');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (Schema::hasColumn('admins', 'authenticator_enabled_at')) {
                $table->dropColumn('authenticator_enabled_at');
            }

            if (Schema::hasColumn('admins', 'authenticator_secret')) {
                $table->dropColumn('authenticator_secret');
            }
        });
    }
};