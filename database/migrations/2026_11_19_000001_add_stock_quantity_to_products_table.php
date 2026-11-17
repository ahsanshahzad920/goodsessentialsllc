<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Optional inventory. NULL (the default for every existing product) means
     * "not tracked": nothing changes for that product. Once the owner enters a
     * number, the store shows real "Only N left" / "Out of stock" labels and
     * checkout decrements it (App\Support\Stock).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('stock_quantity')->nullable()->after('dis_price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock_quantity');
        });
    }
};
