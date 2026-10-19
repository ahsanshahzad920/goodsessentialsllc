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

    // Single source of truth for delivery-time copy, referenced everywhere
    // instead of hardcoding "Karachi" / day-count text per page.
    'delivery' => [
        'message'       => 'Delivery across Pakistan in 1–3 working days. Cash on Delivery available.',
        'message_short' => 'Nationwide in 1–3 working days',
    ],

    // Shipping charges shown at checkout (CheckoutController::shippingFor())
    // and documented on the Shipping Policy page — keep these two in sync.
    // Bug fix: these two keys did not exist before, so checkout was silently
    // charging the shippingFor() fallback of Rs. 50 for every order instead
    // of the Rs. 200 / Rs. 250 already stated on the Shipping Policy page.
    'shipping_online_payment' => (float) env('SHOP_SHIPPING_ONLINE', 200),
    'shipping_cod_payment'    => (float) env('SHOP_SHIPPING_COD', 250),

    'address' => env('SHOP_ADDRESS', '5th Street, 79B Majeed Abad Colony, Bahawalpur, 63100, Pakistan'),

    'currency' => [
        'code'     => env('SHOP_CURRENCY_CODE', 'PKR'),
        'symbol'   => env('SHOP_CURRENCY_SYMBOL', 'Rs.'),
        'position' => env('SHOP_CURRENCY_POSITION', 'before'), // before | after
        'decimals' => (int) env('SHOP_CURRENCY_DECIMALS', 2),  // use 0 for whole rupees
        'space'    => true,                                    // "Rs. 1,299" vs "Rs.1,299"
    ],

    // Used by Organization JSON-LD, the WhatsApp button, and the Contact page
    // / footer / policy pages (single source of truth for all four).
    'contact' => [
        'phone'    => env('SHOP_PHONE', '+923060974495'),
        'whatsapp' => env('SHOP_WHATSAPP', '+923060974495'),
        'email'    => env('SHOP_SUPPORT_EMAIL', 'info@goodsessentialsllc.com'),
        'hours'    => env('SHOP_SUPPORT_HOURS', 'Open 24 hours'),
    ],

    // TODO(owner): social links for Organization schema's sameAs + footer icons.
    'social' => [
        'instagram' => env('SHOP_INSTAGRAM'),
        'facebook'  => env('SHOP_FACEBOOK'),
        'tiktok'    => env('SHOP_TIKTOK'),
        'youtube'   => env('SHOP_YOUTUBE'),
    ],
];
