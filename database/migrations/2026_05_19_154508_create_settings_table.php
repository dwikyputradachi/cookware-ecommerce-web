<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
            });
        }

        if (DB::table('settings')->count() === 0) {
            DB::table('settings')->insert([
                ['key' => 'site_name', 'value' => 'Murazon Shopping Market'],
                ['key' => 'operational_hours', 'value' => '09.00 WIB - 18.00 WIB'],
                ['key' => 'whatsapp', 'value' => '+62 812-703-0826'],
                ['key' => 'email', 'value' => 'customer_service@murazon.com'],
                ['key' => 'facebook_url', 'value' => '#'],
                ['key' => 'instagram_url', 'value' => '#'],
                ['key' => 'whatsapp_url', 'value' => 'https://wa.me/628127030826'],
                ['key' => 'tiktok_url', 'value' => '#'],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
