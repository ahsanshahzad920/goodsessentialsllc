<?php

return [
    // Text logo (only used when logo_image is null)
    'name'    => 'Goods Essentials',
    'tagline' => 'for happy little ones',

    // Path inside /public. Set to null to use the colourful text logo instead.
    'logo_image' => 'images/navbar-logo.png',

    // Cosmetic label/icon/tone overrides, matched to the real Category rows by name.
    // The category list itself comes from the database, not this array.
    'categories' => [
        ['name' => 'Baby Clothing',          'icon' => 'stroller',        'tone' => 'lavender'],
        ['name' => 'Feeding',                'icon' => 'tag',             'tone' => 'sky'],
        ['name' => 'Baby Care & Grooming',   'icon' => 'tag',             'tone' => 'mint'],
        ['name' => 'Mom Care',               'icon' => 'sparkles',        'tone' => 'blush'],
        ['name' => 'Diaper & Mommy Bags',    'icon' => 'leadies_handbag', 'tone' => 'sun'],
        ['name' => 'Toys by Age',            'icon' => 'toys',            'tone' => 'blush'],
        ['name' => 'Gift Boxes',             'icon' => 'gift',            'tone' => 'sun'],
    ],

    // Four coloured promo cards under the category row. category = null links to the full shop.
    'promos' => [
        ['title' => 'Newborn Essentials', 'text' => 'Everything for the first few months', 'tone' => 'lavender', 'category' => 'Baby Clothing', 'image' => 'assets/img/product-4.webp'],
        ['title' => 'Feeding Time', 'text' => 'Breast pumps, bottles & bibs', 'tone' => 'sky', 'category' => 'Feeding', 'image' => 'assets/img/category-3.jpg'],
        ['title' => 'Gift Boxes', 'text' => 'Newborn gifts & aqiqa sets', 'tone' => 'sun', 'category' => 'Gift Boxes', 'image' => 'assets/img/category-1.webp'],
        ['title' => 'Toys by Age', 'text' => 'Gentle play, 0 to 3 years', 'tone' => 'blush', 'category' => 'Toys by Age', 'image' => 'assets/img/category-2.jpg'],
    ],
    'shipping_flat' => 70.00,

    'currency' => [
        'code'     => env('SHOP_CURRENCY_CODE', 'PKR'),
        'symbol'   => env('SHOP_CURRENCY_SYMBOL', 'Rs.'),
        'position' => env('SHOP_CURRENCY_POSITION', 'before'), // before | after
        'decimals' => (int) env('SHOP_CURRENCY_DECIMALS', 2),  // use 0 for whole rupees
        'space'    => true,                                    // "Rs. 1,299" vs "Rs.1,299"
    ],

    // Used by Organization/WebSite JSON-LD and the WhatsApp button.
    // TODO(owner): fill these in — omitted from schema/UI entirely while null,
    // never rendered as a placeholder value.
    'contact' => [
        'phone'    => env('SHOP_PHONE'),
        'whatsapp' => env('SHOP_WHATSAPP'),
        'email'    => env('SHOP_SUPPORT_EMAIL'),
        'hours'    => env('SHOP_SUPPORT_HOURS'),
    ],

    // TODO(owner): social links for Organization schema's sameAs + footer icons.
    'social' => [
        'instagram' => env('SHOP_INSTAGRAM'),
        'facebook'  => env('SHOP_FACEBOOK'),
        'tiktok'    => env('SHOP_TIKTOK'),
        'youtube'   => env('SHOP_YOUTUBE'),
    ],
];
