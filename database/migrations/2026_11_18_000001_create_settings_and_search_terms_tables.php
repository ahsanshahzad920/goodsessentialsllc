<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * settings: admin-editable store settings (key/value), read via
     * App\Support\Settings. Starts empty — every setting has a safe code
     * default, so nothing changes for customers until the owner sets it.
     *
     * search_terms: what customers actually searched for on the shop page,
     * so "popular searches" in the search box are real data, not invented.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key', 100)->primary();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('search_terms', function (Blueprint $table) {
            $table->id();
            $table->string('term', 100)->unique();
            $table->unsignedInteger('hits')->default(1);
            $table->unsignedInteger('results')->default(0);
            $table->timestamp('last_searched_at')->nullable();
            $table->timestamps();

            $table->index(['hits', 'results']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_terms');
        Schema::dropIfExists('settings');
    }
};
