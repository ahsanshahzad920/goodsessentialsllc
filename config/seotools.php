<?php
/**
 * @see https://github.com/artesaos/seotools
 *
 * Every page sets its own title/description/canonical via App\Services\SeoService,
 * so all package defaults below are disabled rather than left at the library's
 * placeholder joke values ("It's Over 9000!").
 */

return [
    'meta' => [
        'defaults' => [
            'title'       => false,
            'titleBefore' => false,
            'description' => false,
            'separator'   => ' | ',
            'keywords'    => [],
            'canonical'   => 'current',
            'robots'      => false,
        ],
        'webmaster_tags' => [
            'google'    => env('GOOGLE_SITE_VERIFICATION'),
            'bing'      => env('BING_SITE_VERIFICATION'),
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],
        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => false,
            'description' => false,
            'url'         => false,
            'type'        => 'website',
            'site_name'   => config('app.name'),
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [],
    ],
    'json-ld' => [
        'defaults' => [
            'title'       => false,
            'description' => false,
            'url'         => false,
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
