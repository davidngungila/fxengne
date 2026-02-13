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

    'oanda' => [
        'api_key' => env('OANDA_API_KEY'),
        'account_id' => env('OANDA_ACCOUNT_ID'),
        'environment' => env('OANDA_ENVIRONMENT', 'practice'), // 'practice' or 'live'
    ],

    'qos' => [
        'api_key' => env('QOS_API_KEY', 'afe0ac8b33cc43d7f62f318e8e6889ba'),
        'ws_url' => env('QOS_WS_URL', 'wss://quote.qos.hk/ws'),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
    ],

    'notifications' => [
        'email_enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', false),
        'email_address' => env('NOTIFICATIONS_EMAIL_ADDRESS'),
    ],

];
