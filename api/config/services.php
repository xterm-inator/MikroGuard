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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL'),
    ],

    'wireguard' => [
        'interface' => env('ROUTEROS_WIREGUARD_INTERFACE'),
        'endpoint' => env('ROUTEROS_WIREGUARD_ENDPOINT'),
        'server_name' => env('ROUTEROS_WIREGUARD_SERVER_NAME', 'WireGuard Server'),
        'dns' => env('ROUTEROS_WIREGUARD_DNS', '1.1.1.1'),
        'allowed_ips' => env('ROUTEROS_WIREGUARD_ALLOWED_IPS', '0.0.0.0/0')
    ]

];
