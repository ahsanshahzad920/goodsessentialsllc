<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // [{"name":"Red","hex":"#ef4444"}, ...]
            $table->json('colors')->nullable()->after('dis_price');
        });

        Schema::table('order_items', function (Blueprint $table) {
            // snapshot of the color the customer chose
            $table->string('color_name', 40)->nullable()->after('product_image');
            $table->string('color_hex', 7)->nullable()->after('color_name');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['color_name', 'color_hex']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('colors');
        });
    }
};
