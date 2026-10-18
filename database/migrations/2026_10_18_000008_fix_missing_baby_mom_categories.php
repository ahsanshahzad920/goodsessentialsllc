<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The earlier category-pivot migration only renamed legacy categories
     * in place and had no fallback to create them when the legacy row
     * didn't exist (the DB was nearly empty by the time it ran, after a
     * migrate:fresh). This creates any of the four that are still missing.
     */
    public function up(): void
    {
        $now = now();

        $categories = [
            ['name' => 'Mom Care', 'slug' => 'mom-care', 'description' => 'Maternity essentials, postpartum recovery and self-care for moms.'],
            ['name' => 'Diaper & Mommy Bags', 'slug' => 'diaper-mommy-bags', 'description' => 'Diaper bags and mommy bags for outings with baby.'],
            ['name' => 'Toys by Age', 'slug' => 'toys-by-age', 'description' => 'Age-appropriate toys for babies and toddlers, 0-3 years.'],
            ['name' => 'Gift Boxes', 'slug' => 'gift-boxes', 'description' => 'Newborn gift sets, baby shower gifts and aqiqa gift boxes.'],
        ];

        foreach ($categories as $category) {
            $exists = DB::table('categories')->where('slug', $category['slug'])->exists();
            if (! $exists) {
                DB::table('categories')->insert([
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        // No-op: avoid deleting categories that may have real products by now.
    }
};
