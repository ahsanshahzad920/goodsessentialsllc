<?php

return [
    // Text logo (only used when logo_image is null)
    'name'    => 'Goods Essentials',
    'tagline' => 'for happy little ones',

    // Path inside /public. Set to null to use the colourful text logo instead.
    'logo_image' => 'images/navbar-logo.png',

    // 'name' must match what your shop route expects: route('shop', $name)
    // Add/remove rows to match your real categories.
    'categories' => [
        ['name' => 'Baby Products',  'icon' => 'stroller', 'tone' => 'lavender'],
        ['name' => 'Beauty Products',  'icon' => 'sparkles', 'tone' => 'blush'],
        ['name' => 'automotive', 'icon' => 'car','tone' => 'sky'],
        ['name' => 'Home and Kitchen','icon' => 'utensils', 'tone' => 'sun'],
        ['name' => 'Books','icon' => 'book', 'tone' => 'sun'],
        ['name' => 'Clothes','icon' => 'clothing', 'tone' => 'sky'],
        ['name' => 'Gift Boxes','icon' => 'gift', 'tone' => 'sun'],
        ['name' => 'Ladies Bags','icon' => 'leadies_handbag', 'tone' => 'lavender'],
        ['name' => 'Toys','icon' => 'toys', 'tone' => 'blush'],
        ],

    // Four coloured promo cards under the category row. category = null links to the full shop.
    'promos' => [
        ['title' => 'Baby Products', 'text' => 'Fresh picks just for you',    'tone' => 'lavender', 'category' => 'Baby Products', 'image' => 'assets/img/product-4.webp'],
        ['title' => 'Beauty Products', 'text' => 'Everything for little ones', 'tone' => 'sun',      'category' => 'Beauty Products', 'image' => 'assets/img/category-3.jpg'],
        ['title' => 'Gift Boxes', 'text' => 'Make home feel good',         'tone' => 'mint',     'category' => 'Books', 'image' => 'assets/img/category-1.webp'],
        ['title' => 'Toys', 'text' => 'Gentle, everyday care',       'tone' => 'blush',    'category' => 'Clothes', 'image' => 'assets/img/category-2.jpg'],
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
