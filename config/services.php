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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    
    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
        'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
    ],
    
    // 'msgclub' => [
    //     // 'auth_key' => env('MSGCLUB_AUTH_KEY', 'f75d8543c06120a3f674417b6bb8802c'),
    //     'auth_key' => env('MSGCLUB_AUTH_KEY', '88cba4d385f3e1b26aa8dfc9a819262'),
    //     'sender_id' => env('MSGCLUB_SENDER_ID', 'YOUTHC'),
    //     'base_url' => env('MSGCLUB_BASE_URL', 'http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms'),
    // ],

    'msgclub' => [
        'auth_key' => env('MSGCLUB_AUTH_KEY', '88cba4d385f3e1b26aa8dfc9a819262'),
        'sender_id' => env('MSGCLUB_SENDER_ID', 'YCNTUR'),
        'base_url' => env('MSGCLUB_BASE_URL', 'http://msg.msgclub.net/rest/services/sendSMS/sendGroupSms'),
    ],

    'google_maps' => ['key' => env('GOOGLE_MAPS_KEY')],

];
