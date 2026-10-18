<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * A manual migrate:fresh wiped the redirects table (and the one-time seed
     * migration re-ran against a near-empty DB, seeding nothing). This maps
     * the original pre-SEO category names straight to their final slugs
     * post-IA-pivot, plus current real products.
     */
    public function up(): void
    {
        $now = now();
        $rows = [];

        $legacyNameToSlug = [
            'Baby Products' => 'baby-clothing',
            'Baby products' => 'baby-clothing',
            'Beauty Products' => 'mom-care',
            'Ladies Bags' => 'diaper-mommy-bags',
            'Toys' => 'toys-by-age',
            'Gift boxes' => 'gift-boxes',
            'Gift Boxes' => 'gift-boxes',
        ];

        foreach ($legacyNameToSlug as $oldName => $newSlug) {
            $exists = DB::table('categories')->where('slug', $newSlug)->exists();
            if ($exists) {
                $rows[] = [
                    'old_path' => 'shop/' . $oldName,
                    'new_path' => 'shop/' . $newSlug,
                    'status_code' => 301,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (DB::table('products')->get(['id', 'slug']) as $product) {
            $rows[] = [
                'old_path' => 'product-details/' . $product->id,
                'new_path' => 'product/' . $product->slug,
                'status_code' => 301,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach ($rows as $row) {
            DB::table('redirects')->updateOrInsert(['old_path' => $row['old_path']], $row);
        }
    }

    public function down(): void
    {
        // No-op.
    }
};
