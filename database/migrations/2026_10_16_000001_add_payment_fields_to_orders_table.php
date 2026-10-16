<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method', 20)->default('cod')->after('total'); // cod | card
            $table->string('payment_reference')->nullable()->after('payment_status'); // gateway transaction id
            $table->timestamp('paid_at')->nullable()->after('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_reference', 'paid_at']);
        });
    }
};
