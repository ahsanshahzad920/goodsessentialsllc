<?php

return [
    // Text logo (only used when logo_image is null)
    'name'    => 'Good Essentials',
    'tagline' => 'for happy little ones',

    // Path inside /public. Set to null to use the colourful text logo instead.
    'logo_image' => 'images/navbar-logo.jpeg',

    // 'name' must match what your shop route expects: route('shop', $name)
    // Add/remove rows to match your real categories.
    'categories' => [
        ['name' => 'Baby Products',  'icon' => 'stroller', 'tone' => 'lavender'],
        ['name' => 'Beauty Products',  'icon' => 'sparkles', 'tone' => 'blush'],
        ['name' => 'automotive', 'icon' => 'car','tone' => 'sky'],
        ['name' => 'Home and Kitchen','icon' => 'utensils', 'tone' => 'sun'],
        ['name' => 'Books','icon' => 'book', 'tone' => 'sun'],
        ['name' => 'Clothes','icon' => 'clothing', 'tone' => 'sun'],
        ],

    // Four coloured promo cards under the category row. category = null links to the full shop.
    'promos' => [
        ['title' => 'Baby Products', 'text' => 'Fresh picks just for you',    'tone' => 'lavender', 'category' => 'Baby Products', 'image' => 'assets/img/product-4.webp'],
        ['title' => 'Beauty Products', 'text' => 'Everything for little ones', 'tone' => 'sun',      'category' => 'Beauty Products', 'image' => 'assets/img/category-3.jpg'],
        ['title' => 'Books', 'text' => 'Make home feel good',         'tone' => 'mint',     'category' => 'Books', 'image' => 'assets/img/category-1.webp'],
        ['title' => 'Clothes', 'text' => 'Gentle, everyday care',       'tone' => 'blush',    'category' => 'Clothes', 'image' => 'assets/img/category-2.jpg'],
    ],
];
