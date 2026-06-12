<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_promo')->default(false)->after('price');
            $table->integer('discount_price')->nullable()->after('is_promo');
            $table->integer('total_sold')->default(0)->after('stock');
            $table->float('rating')->default(5.0)->after('total_sold');
        });
    }
};
