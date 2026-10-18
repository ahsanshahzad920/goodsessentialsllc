<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Pivots the catalog's information architecture to baby + mom only.
     * Idempotent by slug, so it's safe to run against a fresh install or
     * an install that still has the original 5 generic categories.
     */
    public function up(): void
    {
        $now = now();

        // Rename legacy categories in place if they exist (preserves ids/relations),
        // otherwise create them fresh.
        $renames = [
            'Beauty Products' => ['name' => 'Mom Care', 'slug' => 'mom-care', 'description' => 'Maternity essentials, postpartum recovery and self-care for moms.'],
            'Ladies Bags'      => ['name' => 'Diaper & Mommy Bags', 'slug' => 'diaper-mommy-bags', 'description' => 'Diaper bags and mommy bags for outings with baby.'],
            'Toys'             => ['name' => 'Toys by Age', 'slug' => 'toys-by-age', 'description' => 'Age-appropriate toys for babies and toddlers, 0-3 years.'],
            'Gift boxes'       => ['name' => 'Gift Boxes', 'slug' => 'gift-boxes', 'description' => 'Newborn gift sets, baby shower gifts and aqiqa gift boxes.'],
            'Gift Boxes'       => ['name' => 'Gift Boxes', 'slug' => 'gift-boxes', 'description' => 'Newborn gift sets, baby shower gifts and aqiqa gift boxes.'],
        ];

        foreach ($renames as $oldName => $data) {
            $existing = DB::table('categories')->where('name', $oldName)->first();
            if ($existing && ! DB::table('categories')->where('slug', $data['slug'])->where('id', '!=', $existing->id)->exists()) {
                DB::table('categories')->where('id', $existing->id)->update([
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'description' => $data['description'],
                    'updated_at' => $now,
                ]);
            }
        }

        // Baby Products becomes the parent of the new age/type subcategories.
        $babyParentId = DB::table('categories')->whereIn('name', ['Baby Products', 'Baby products'])->value('id');

        if (! $babyParentId) {
            $babyParentId = DB::table('categories')->insertGetId([
                'name' => 'Baby Clothing',
                'slug' => 'baby-clothing',
                'description' => 'Everyday and seasonal clothing for babies and toddlers.',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            DB::table('categories')->where('id', $babyParentId)->update([
                'name' => 'Baby Clothing',
                'slug' => 'baby-clothing',
                'description' => 'Everyday and seasonal clothing for babies and toddlers.',
                'updated_at' => $now,
            ]);
        }

        $subcategories = [
            ['name' => 'Newborn (0-3 months)', 'slug' => 'newborn-0-3-months', 'description' => 'Clothing sized for newborns, 0 to 3 months.'],
            ['name' => 'Winter Suits & Fleece Rompers', 'slug' => 'winter-suits-fleece-rompers', 'description' => 'Warm winter suits and fleece rompers for babies.'],
            ['name' => 'Summer Wear', 'slug' => 'summer-wear', 'description' => 'Lightweight, breathable summer clothing for babies.'],
            ['name' => 'Caps, Socks & Mittens', 'slug' => 'caps-socks-mittens', 'description' => 'Caps, socks and mittens for babies.'],
        ];

        foreach ($subcategories as $sub) {
            $exists = DB::table('categories')->where('slug', $sub['slug'])->exists();
            if (! $exists) {
                DB::table('categories')->insert([
                    'name' => $sub['name'],
                    'slug' => $sub['slug'],
                    'description' => $sub['description'],
                    'parent_id' => $babyParentId,
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // New top-level categories with no legacy equivalent.
        $newTopLevel = [
            ['name' => 'Feeding', 'slug' => 'feeding', 'description' => 'Breast pumps, feeding bottles, bibs and burp cloths.'],
            ['name' => 'Baby Care & Grooming', 'slug' => 'baby-care-grooming', 'description' => 'Electric nail trimmers, bath and skin care, health and safety essentials.'],
        ];

        foreach ($newTopLevel as $cat) {
            $exists = DB::table('categories')->where('slug', $cat['slug'])->exists();
            if (! $exists) {
                DB::table('categories')->insert([
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'description' => $cat['description'],
                    'created_by' => 1,
                    'updated_by' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Final safety net: any category still missing a slug gets one from its name.
        $existingSlugs = DB::table('categories')->pluck('slug')->filter()->all();
        foreach (DB::table('categories')->whereNull('slug')->orWhere('slug', '')->get() as $category) {
            $base = Str::slug($category->name) ?: "category-{$category->id}";
            $slug = $base;
            $suffix = 2;
            while (in_array($slug, $existingSlugs, true)) {
                $slug = "{$base}-{$suffix}";
                $suffix++;
            }
            $existingSlugs[] = $slug;
            DB::table('categories')->where('id', $category->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        // Intentionally no-op: this migration only renames/seeds category rows,
        // reversing it would silently relabel real merchandising data.
    }
};
