<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('product_title');
            $table->string('sku')->nullable()->unique()->after('slug');
            $table->string('meta_title')->nullable()->after('product_description');
            $table->string('meta_description', 320)->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
            $table->string('brand')->nullable()->after('og_image');
            $table->string('color')->nullable()->after('brand');
            $table->string('size')->nullable()->after('color');
            $table->string('material')->nullable()->after('size');
            $table->unsignedSmallInteger('suggested_min_age_months')->nullable()->after('material');
            $table->unsignedSmallInteger('suggested_max_age_months')->nullable()->after('suggested_min_age_months');
        });

        $existingSlugs = [];
        foreach (DB::table('products')->orderBy('id')->get(['id', 'product_title']) as $product) {
            $base = Str::slug($product->product_title) ?: "product-{$product->id}";
            $slug = $base;
            $suffix = 2;
            while (in_array($slug, $existingSlugs, true)) {
                $slug = "{$base}-{$suffix}";
                $suffix++;
            }
            $existingSlugs[] = $slug;
            DB::table('products')->where('id', $product->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'sku', 'meta_title', 'meta_description', 'og_image',
                'brand', 'color', 'size', 'material',
                'suggested_min_age_months', 'suggested_max_age_months',
            ]);
        });
    }
};
