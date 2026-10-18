<?php

/**
 * TODO(owner): every value below is null until set in .env. Each snippet in
 * resources/views/layouts/guest.blade.php is fully inert (renders nothing)
 * while its key here is null — safe to deploy before these are filled in.
 */

return [
    'ga4_id' => env('GA4_MEASUREMENT_ID'),
    'meta_pixel_id' => env('META_PIXEL_ID'),
    'tiktok_pixel_id' => env('TIKTOK_PIXEL_ID'),
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
    'bing_site_verification' => env('BING_SITE_VERIFICATION'),
];
