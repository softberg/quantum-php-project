<?php

return [
    /**
     * ---------------------------------------------------------
     * Current mailer settings
     * ---------------------------------------------------------
     *
     * Current configuration to use.
     */
    'current' => 'smtp',

    'mail_trap' => true,
    
    'smtp' => [
        'host' => env('MAIL_HOST'),
        'secure' => env('MAIL_SMTP_SECURE'),
        'port' => env('MAIL_PORT'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
    ],

    'sendinblue' => [
        'api_key' => env("SENDINBLUE_APIKEY", null),
    ],
    
    'sendgrid' => [
        'api_key' => env("SENDGRID_APIKEY", null),
    ],

    'mailgun' => [
        'api_key' => env("MAILGUN_APIKEY", null),
        'domain' => env("MAILGUN_DOMAIN", null),
    ],
    
    'mandrill' => [
        'api_key' => env("MANDRILL_APIKEY", null),
    ],
];
