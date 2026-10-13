<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'qrisly' => [
        'api_key' => env('QRISLY_API_KEY', '9vBjwpmb248f87abf1ac249e1DPKzgY5'),
        'qris_id' => (int) env('QRISLY_QRIS_ID', 1277),
        'env' => env('QRISLY_ENV', 'sandbox'),
        'verify_ssl' => env('QRISLY_VERIFY_SSL', false),
        'merchant_name' => env('QRISLY_MERCHANT_NAME', 'Reloo Marketplace'),
    ],

];
