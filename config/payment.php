<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure payment gateways and settings for the platform
    |
    */

    // Enable/disable payment system
    'enabled' => env('PAYMENT_ENABLED', false),

    // Test mode for payments (when true, no real charges are made)
    'test_mode' => env('PAYMENT_TEST_MODE', true),

    // Supported payment gateways
    'gateways' => [
        'stripe' => [
            'enabled' => env('STRIPE_ENABLED', false),
            'key' => env('STRIPE_KEY'),
            'secret' => env('STRIPE_SECRET'),
        ],
        'paypal' => [
            'enabled' => env('PAYPAL_ENABLED', false),
            'client_id' => env('PAYPAL_CLIENT_ID'),
            'secret' => env('PAYPAL_SECRET'),
            'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox or live
        ],
        'kaspi' => [
            'enabled' => env('KASPI_ENABLED', false),
            'merchant_id' => env('KASPI_MERCHANT_ID'),
            'secret' => env('KASPI_SECRET'),
        ],
    ],

    // Currency
    'currency' => env('PAYMENT_CURRENCY', 'RUB'),
    'currency_symbol' => env('PAYMENT_CURRENCY_SYMBOL', '₽'),

    // Commission (percentage)
    'platform_commission' => env('PLATFORM_COMMISSION', 10),

    // Minimum course price
    'min_course_price' => env('MIN_COURSE_PRICE', 100),

    // Coupons
    'coupons_enabled' => env('COUPONS_ENABLED', true),

    // Subscriptions
    'subscriptions_enabled' => env('SUBSCRIPTIONS_ENABLED', false),
];
