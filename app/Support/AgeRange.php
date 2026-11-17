<?php

namespace App\Support;

/**
 * "Shop by Age" buckets, in months. A product belongs to a bucket when its
 * own suitable range (suggested_min/max_age_months) overlaps the bucket.
 * Products with no age data belong to none — ages are never guessed.
 */
class AgeRange
{
    /** key => [label, fromMonth, toMonth] */
    public const BUCKETS = [
        '0-3m'  => ['0–3 months', 0, 3],
        '3-6m'  => ['3–6 months', 3, 6],
        '6-12m' => ['6–12 months', 6, 12],
        '1-3y'  => ['1–3 years', 12, 36],
    ];

    public static function valid(?string $key): bool
    {
        return $key !== null && isset(self::BUCKETS[$key]);
    }

    public static function label(string $key): string
    {
        return self::BUCKETS[$key][0] ?? $key;
    }
}
