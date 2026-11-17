<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Phase 0 catalog cleanup (store redesign):
     *
     * 1. Internal codes out of customer-facing titles. "SW-7 Valvet Baby ..."
     *    becomes "Velvet Baby ..." with "SW-7" moved into products.sku
     *    (which already exists, unique, and is empty on every live row).
     *    Handles a leading code ("WCP-2 ...") and a trailing one ("... | MB-8001").
     *    Manufacturer model numbers mid-title (e.g. "Blueidea BLD-100") are
     *    real product information and are left alone.
     * 2. "Valvet" -> "Velvet" typo in title / meta / description.
     * 3. Decorative straight/curly double quotes stripped from titles
     *    ('"Smile" Pom-Pom' -> 'Smile Pom-Pom').
     * 4. "Innovative Gadgets" (only holds the massage gun) renamed to
     *    "Mom & Wellness" so non-baby items live in their own category and can
     *    be excluded from homepage baby sections. Old URL 301s to the new one.
     * 5. categories.image: optional admin-uploaded tile image.
     *
     * Slugs are deliberately NOT touched: ads and shared links keep working.
     * Rows are updated via DB::table so no model events regenerate slugs.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('description');
        });

        $takenSkus = DB::table('products')->whereNotNull('sku')->where('sku', '!=', '')->pluck('sku')->all();
        $products = DB::table('products')->orderBy('id')->get();

        $tidy = fn (string $t) => trim(preg_replace('/\s{2,}/u', ' ',
            preg_replace('/\bValvet\b/u', 'Velvet', str_replace(['"', '“', '”'], '', $t))));

        // Pass 1: proposed customer-facing title + extracted code per product.
        $plan = [];
        foreach ($products as $p) {
            $title = (string) $p->product_title;
            $code = null;
            if (preg_match('/^([A-Z]{1,4}-\d+)\s+(.+)$/u', $title, $m)) {
                [$code, $title] = [$m[1], $m[2]];
            } elseif (preg_match('/^(.+?)\s*\|\s*([A-Z]{1,4}-\d+)\s*$/u', $title, $m)) {
                [$title, $code] = [$m[1], $m[2]];
            }
            $plan[$p->id] = ['code' => $code, 'stripped' => $tidy($title), 'kept' => $tidy((string) $p->product_title)];
        }

        // Where the code is the ONLY thing telling products apart (SW-1/SW-4/SW-5
        // all become "Baby Wrapping Swaddle Bed Set"), stripping it would make
        // identical titles. Those keep their code in the title until the owner
        // gives each a distinguishing name; their SKU is still filled in.
        $counts = array_count_values(array_map(fn ($x) => mb_strtolower($x['stripped']), $plan));

        foreach ($products as $p) {
            $row = $plan[$p->id];
            $unique = $counts[mb_strtolower($row['stripped'])] === 1;
            $title = $row['code'] && $unique ? $row['stripped'] : $row['kept'];

            $sku = $p->sku;
            if ($row['code'] && ! $sku && ! in_array($row['code'], $takenSkus, true)) {
                $sku = $row['code'];
                $takenSkus[] = $row['code'];
            }

            $fix = fn ($v) => $v === null ? null : preg_replace('/\bValvet\b/u', 'Velvet', $v);

            DB::table('products')->where('id', $p->id)->update([
                'product_title'       => $title,
                'sku'                 => $sku ?: null,
                'meta_title'          => $fix($p->meta_title),
                'meta_description'    => $fix($p->meta_description),
                'product_description' => $fix($p->product_description),
            ]);
        }

        $gadgets = DB::table('categories')->where('slug', 'innovative-gadgets')->first();
        if ($gadgets && ! DB::table('categories')->where('slug', 'mom-wellness')->exists()) {
            $now = now();

            DB::table('categories')->where('id', $gadgets->id)->update([
                'name'       => 'Mom & Wellness',
                'slug'       => 'mom-wellness',
                'updated_at' => $now,
            ]);

            DB::table('redirects')->updateOrInsert(
                ['old_path' => 'shop/innovative-gadgets'],
                ['new_path' => 'shop/mom-wellness', 'status_code' => 301, 'created_at' => $now, 'updated_at' => $now]
            );
            // Keep older redirect chains pointing straight at the final URL.
            DB::table('redirects')->where('new_path', 'shop/innovative-gadgets')
                ->update(['new_path' => 'shop/mom-wellness', 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        // Title/SKU edits are content fixes and are not reversed automatically:
        // take a DB backup before migrating (see deploy notes).
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
