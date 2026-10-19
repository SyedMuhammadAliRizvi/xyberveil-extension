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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'),
        'client_secret' => env('APPLE_CLIENT_SECRET'),
        'redirect' => env('APPLE_REDIRECT_URI'),
        'team_id' => env('APPLE_TEAM_ID'),
        'key_id' => env('APPLE_KEY_ID'),
        'private_key' => env('APPLE_PRIVATE_KEY'),
    ],

    'onesignal' => [
        'app_id' => env('ONESIGNAL_APP_ID'),
        'api_key' => env('ONESIGNAL_API_KEY'),
    ],

    'script_runner' => [
        'url' => env('SCRIPT_BASE_URL', 'https://scripts.tecclubb.com'),
    ],

    'ipinfo' => [
        'token' => env('IPINFO_TOKEN'),
    ],

    'proxy_monitor' => [
        'base_url' => env('PROXY_MONITOR_BASE_URL'),
        'admin_user' => env('PROXY_MONITOR_ADMIN_USER'),
        'admin_pass' => env('PROXY_MONITOR_ADMIN_PASS'),
    ],

    'proxy' => [
        'agent_download_url' => env('PROXY_AGENT_DOWNLOAD_URL', env('APP_URL')),
        'allowed_agent_ip' => env('PROXY_ALLOWED_AGENT_IP', '127.0.0.1'),
    ],

    'appstore' => [
        'bundle_id' => env('APPSTORE_BUNDLE_ID'),
        'environment' => env('APPSTORE_ENVIRONMENT', 'Production'), // or 'Sandbox'
    ],

    'google_play' => [
        'package_name' => env('GOOGLE_PLAY_PACKAGE_NAME'),
        'service_account_json' => env('GOOGLE_PLAY_SERVICE_ACCOUNT_JSON'),
    ],

    'edis' => [
        'email' => env('EDIS_EMAIL'),
        'password' => env('EDIS_PASSWORD'),
    ],

    'oneprovider' => [
        'api_key' => env('ONEPROVIDER_API_KEY'),
        'client_key' => env('ONEPROVIDER_CLIENT_KEY'),
    ],

    'ovh' => [
        'endpoint' => env('OVH_ENDPOINT', 'ovh-eu'),
        'app_key' => env('OVH_APP_KEY'),
        'app_secret' => env('OVH_APP_SECRET'),
        'consumer_key' => env('OVH_CONSUMER_KEY'),
    ],

];
