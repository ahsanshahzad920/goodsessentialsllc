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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->foreignId('parent_id')->nullable()->after('slug')
                ->constrained('categories')->nullOnDelete();
            $table->string('meta_title')->nullable()->after('description');
            $table->string('meta_description', 320)->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
        });

        $existingSlugs = [];
        foreach (DB::table('categories')->orderBy('id')->get(['id', 'name']) as $category) {
            $base = Str::slug($category->name);
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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
            $table->dropColumn(['slug', 'meta_title', 'meta_description', 'og_image']);
        });
    }
};
