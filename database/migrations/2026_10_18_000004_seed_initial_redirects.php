<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [];

        foreach (DB::table('categories')->get(['name', 'slug']) as $category) {
            $rows[] = [
                'old_path' => 'shop/' . $category->name,
                'new_path' => 'shop/' . $category->slug,
                'status_code' => 301,
                'created_at' => $now,
                'updated_at' => $now,
            ];
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
        DB::table('redirects')->where('new_path', 'LIKE', 'shop/%')
            ->orWhere('new_path', 'LIKE', 'product/%')
            ->delete();
    }
};
