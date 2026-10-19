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

    // Optional: once set, App\Services\TikTokEventsApi can send the same
    // events server-side for deduplication with the Pixel (matched on
    // event_id). Fully inert — nothing calls this yet — while null.
    'tiktok_events_api_token' => env('TIKTOK_EVENTS_API_TOKEN'),
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),
    'bing_site_verification' => env('BING_SITE_VERIFICATION'),
];
