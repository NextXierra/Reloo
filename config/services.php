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

    'rajaongkir' => [
        'api_key' => env('RAJAONGKIR_API_KEY', 'MDbaQJym248f87abf1ac249e3qdXbx5I'),
        'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
        'origin_id' => (int) env('RAJAONGKIR_DEFAULT_ORIGIN_ID', 17473),
        'timeout' => (int) env('RAJAONGKIR_TIMEOUT', 10),
        'verify_ssl' => env('RAJAONGKIR_VERIFY_SSL', false),
    ],

    'komerce_delivery' => [
        'api_key' => env('KOMERCE_DELIVERY_API_KEY', 'MDbaQJym248f87abf1ac249e3qdXbx5I'),
        'env' => env('KOMERCE_DELIVERY_ENV', 'sandbox'),
        'base_url' => env('KOMERCE_DELIVERY_BASE_URL', 'https://api-sandbox.collaborator.komerce.id'),
        'shipper_name' => env('KOMERCE_SHIPPER_NAME', 'Reloo Store'),
        'shipper_phone' => env('KOMERCE_SHIPPER_PHONE', '081234567890'),
        'shipper_email' => env('KOMERCE_SHIPPER_EMAIL', 'admin@reloo.id'),
        'shipper_address' => env('KOMERCE_SHIPPER_ADDRESS', 'Jl. Reloo Central Hub No. 1, Jakarta'),
        'shipper_destination_id' => (int) env('KOMERCE_SHIPPER_DESTINATION_ID', 17473),
        'origin_pin_point' => env('KOMERCE_ORIGIN_PIN_POINT', '-6.175392, 106.827153'),
        'timeout' => (int) env('KOMERCE_DELIVERY_TIMEOUT', 12),
        'verify_ssl' => env('KOMERCE_DELIVERY_VERIFY_SSL', false),
    ],

];
