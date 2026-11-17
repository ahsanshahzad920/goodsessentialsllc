<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SearchTerm extends Model
{
    protected $fillable = ['term', 'hits', 'results', 'last_searched_at'];

    protected $casts = ['last_searched_at' => 'datetime', 'hits' => 'integer', 'results' => 'integer'];

    /** Record a submitted shop search (not keystrokes). Never breaks the page. */
    public static function record(?string $term, int $results): void
    {
        $term = Str::lower(trim(preg_replace('/\s+/', ' ', (string) $term)));
        if (mb_strlen($term) < 2 || mb_strlen($term) > 100) {
            return;
        }

        rescue(function () use ($term, $results) {
            $now = now();
            DB::table('search_terms')->upsert(
                [['term' => $term, 'hits' => 1, 'results' => $results, 'last_searched_at' => $now, 'created_at' => $now, 'updated_at' => $now]],
                ['term'],
                ['hits' => DB::raw('hits + 1'), 'results' => $results, 'last_searched_at' => $now, 'updated_at' => $now]
            );
        }, report: false);
    }

    /**
     * Popular searches for the empty search box: only terms several people
     * searched AND that return products. Empty until real data exists.
     */
    public static function popular(int $limit = 6): array
    {
        return rescue(fn () => static::where('hits', '>=', 3)->where('results', '>', 0)
            ->orderByDesc('hits')->take($limit)->pluck('term')->all(), [], false);
    }
}
