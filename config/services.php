<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'mercadopago' => [
        'client_id' => env('MERCADO_PAGO_CLIENT_ID'),
        'client_secret' => env('MERCADO_PAGO_CLIENT_SECRET'),
        'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN'),
        'redirect_uri' => env('MERCADO_PAGO_REDIRECT_URI'),
    ],

    // Secret usado para gerar hashes de pedidos, lotes, cupons, etc.
    'hash_secret' => env('HASH_SECRET', '7bc05eb02415fe73101eeea0180e258d45e8ba2b'),

];
