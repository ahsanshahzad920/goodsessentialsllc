<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-editable store settings with code defaults.
 *
 *   Settings::get('free_delivery_threshold')       // null = feature off
 *   Settings::freeDeliveryThreshold()               // ?float
 *   Settings::set(['free_delivery_threshold' => 3000])
 *
 * All rows are cached as one array; any write clears the cache.
 */
class Settings
{
    private const CACHE_KEY = 'shop.settings';

    /** Known settings and their defaults (used when no row exists). */
    public const DEFAULTS = [
        // Free delivery on orders whose subtotal reaches this amount (PKR).
        // Empty = no free delivery, and the announcement bar does not claim it.
        'free_delivery_threshold' => null,
        'announcement_enabled'    => '1',
    ];

    public static function all(): array
    {
        $rows = Cache::get(self::CACHE_KEY);
        if ($rows === null) {
            // Before the migration has run (or if the DB is unreachable) render
            // with defaults and do not cache, so real settings are picked up as
            // soon as they can be read. Settings must never take the store down.
            $rows = rescue(function () {
                return Schema::hasTable('settings') ? DB::table('settings')->pluck('value', 'key')->all() : null;
            }, null, false);
            if ($rows === null) {
                return self::DEFAULTS;
            }
            Cache::forever(self::CACHE_KEY, $rows);
        }

        return array_merge(self::DEFAULTS, $rows);
    }

    public static function get(string $key, $default = null)
    {
        $value = self::all()[$key] ?? null;

        return $value === null || $value === '' ? $default : $value;
    }

    public static function set(array $values): void
    {
        $now = now();
        foreach ($values as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value === null ? null : (string) $value, 'updated_at' => $now, 'created_at' => $now]
            );
        }
        Cache::forget(self::CACHE_KEY);
    }

    public static function freeDeliveryThreshold(): ?float
    {
        $v = self::get('free_delivery_threshold');

        return is_numeric($v) && (float) $v > 0 ? (float) $v : null;
    }

    public static function bool(string $key): bool
    {
        return filter_var(self::get($key, '0'), FILTER_VALIDATE_BOOLEAN);
    }
}
