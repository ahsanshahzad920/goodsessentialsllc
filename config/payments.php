<?php

return [
    /*
    | Which gateway handles "Card Payment". Leave empty to hide the card option.
    |   local testing : PAYMENT_GATEWAY=fake
    |   production    : the gateway you choose (a driver class is added under "gateways")
    */
    'gateway' => env('PAYMENT_GATEWAY'),

    'gateways' => [
        'fake' => \App\Payments\Gateways\FakeGateway::class,
        'payfast' => \App\Payments\Gateways\PayFastGateway::class,
        // 'stripe'  => \App\Payments\Gateways\StripeGateway::class,
        // 'safepay' => \App\Payments\Gateways\SafepayGateway::class,
    ],

    // Payment methods shown on checkout (in this order)
    'methods' => [
        'cod' => [
            'label'       => 'Cash on Delivery',
            'description' => 'Pay in cash when your order arrives.',
            'icon'        => 'banknote',
            'enabled'     => true,
        ],
        'card' => [
            'label'       => 'Card Payment',
            'description' => 'Pay securely online with your debit or credit card.',
            'icon'        => 'credit-card',
            'enabled'     => true, // still hidden automatically when no gateway is configured
        ],
    ],
];
