<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'paypal' => [
        'client_id' => 'ASJvMffow70F6fu54K5Y4Fw_ZPhMuxklRX2gDOrV42iJ8t288YRUHQOWpXsJxFe12Bkab1dOJfCWwpe0',
        'secret' => 'EB8K8h2uFrHzu8RLdzEC7Th_SU6JBOucr33KIdNMvThCi2M1XF3aFRmK_C7odvLnOlAxS_RBESNtkOsp',
    ],

];
