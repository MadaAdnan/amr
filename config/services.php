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
    'stripe' => [
        'STRIPE_SECRET' => env('STRIPE_SECRET','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'),
        'STRIPE_KEY' => env('STRIPE_KEY'),
    ],
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' =>env('RECAPTCHA_SECRET_KEY'),
    ],
    'google' => [    
        'client_id' => env('GOOGLE_CLIENT_ID'),  
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),  
        'redirect' => env('GOOGLE_REDIRECT_URI') 
      ],      
    'apple' => [
         'client_id' => env('APPLE_CLIENT_ID_MOBILE_APP'),
         'client_secret' => "***AUTOGENERATED ON THE FLY***",
         'redirect' => env('APPLE_REDIRECT'),
         'team_id' => env('APPLE_TEAM_ID'),
         'key_id' => env('APPLE_KEY_ID'),
         'private_key' => file_get_contents(__DIR__ . '/AuthKey.p8'),
    ],
];
