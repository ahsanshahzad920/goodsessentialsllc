<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });

        // Existing single images become the main image of each product's gallery.
        DB::table('products')
            ->whereNotNull('product_image')
            ->where('product_image', '!=', '')
            ->orderBy('id')
            ->chunkById(200, function ($products) {
                $now = now();

                DB::table('product_images')->insert(
                    $products->map(fn ($product) => [
                        'product_id' => $product->id,
                        'path'       => $product->product_image,
                        'sort_order' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all()
                );
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
