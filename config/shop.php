<?php

return [
    // Text logo (only used when logo_image is null)
    'name'    => 'Goods Essentials',
    'tagline' => 'for happy little ones',

    // Path inside /public. Set to null to use the colourful text logo instead.
    'logo_image' => 'images/navbar-logo.png',

    // Cosmetic label/icon/tone overrides, matched to the real Category rows by name.
    // The category list itself comes from the database, not this array.
    // Optional 'text' is the subtitle on the homepage collection tile.
    'categories' => [
        ['name' => 'Baby Bedding',                  'icon' => 'bed',             'tone' => 'lavender', 'text' => 'Swaddles & cosy bed sets'],
        ['name' => 'Winter Suits & Fleece Rompers', 'icon' => 'snowflake',       'tone' => 'sky',      'text' => 'Warm suits, caps & rompers'],
        ['name' => 'Baby Toys',                     'icon' => 'toys',            'tone' => 'blush',    'text' => 'Play gyms, swings & more'],
        ['name' => 'Baby Care',                     'icon' => 'sparkles',        'tone' => 'mint',     'text' => 'Grooming & gift sets'],
        ['name' => 'Mom & Wellness',                'icon' => 'heart',           'tone' => 'sun',      'text' => 'Self-care for mom'],
        ['name' => 'Baby Clothing',                 'icon' => 'clothing',        'tone' => 'lavender'],
        ['name' => 'Diaper & Mommy Bags',           'icon' => 'leadies_handbag', 'tone' => 'sun'],
        ['name' => 'Gift Boxes',                    'icon' => 'gift',            'tone' => 'sun'],
    ],

    // Category slugs kept out of homepage baby sections (Popular Picks, Deals,
    // collection tiles). They still appear in the nav, shop and search.
    'homepage_exclude_categories' => ['mom-wellness'],
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
        // Bug fix: every product price is a whole rupee amount, so the old
        // default of 2 decimals rendered a needless "Rs. 5,400.00" everywhere
        // instead of "Rs. 5,400" — inconsistent with how prices are written
        // on promo banners and on the product pages themselves.
        'decimals' => (int) env('SHOP_CURRENCY_DECIMALS', 0),
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
    'admin_emails' => array_filter(
        array_map('trim', explode(',', env('ADMIN_EMAILS', 'info@goodsessentialsllc.com')))
    ),
];
