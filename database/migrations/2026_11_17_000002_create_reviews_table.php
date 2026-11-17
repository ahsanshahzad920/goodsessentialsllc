<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Real customer reviews, replacing the testimonials hardcoded in
     * index.blade.php. Only the two testimonials that were already shown on
     * the live homepage AND are about this store are carried over; the third
     * ("suits for future events") was about clothing this store does not sell
     * and is dropped. Nothing new is invented here.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('name', 80);
            $table->string('city', 80)->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->string('photo')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->index(['is_approved', 'is_featured']);
            $table->index(['product_id', 'is_approved']);
        });

        $now = now();
        DB::table('reviews')->insert([
            [
                'name' => 'Ayesha Khan', 'city' => 'Gujranwala', 'rating' => 5,
                'body' => 'I had a wonderful experience with GoodsEssentials. Their customer service was awesome and patient while I added and removed items from my order. They made sure everything was perfect.',
                'is_featured' => true, 'is_approved' => true, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'Amir', 'city' => 'Pattoki', 'rating' => 5,
                'body' => 'Mera shopping experience bht bht axha rha. Products ky rates bht munasib hn, must buy thanks.',
                'is_featured' => true, 'is_approved' => true, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
