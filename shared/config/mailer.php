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
        'mail_host' => env('MAIL_HOST'),
        'mail_secure' => env('MAIL_SMTP_SECURE'),
        'mail_port' => env('MAIL_PORT'),
        'mail_username' => env('MAIL_USERNAME'),
        'mail_password' => env('MAIL_PASSWORD'),
    ],

    'sendinblue' => [
        'api_key' => env("SENDINBLUE_APIKEY", null),
    ],

    'mailgun' => [
        'api_key' => env("MAILGUN_APIKEY", null),
        'domain' => env("MAILGUN_DOMAIN", null),
    ],
    
    'mandrill' => [
        'api_key' => env("MANDRILL_APIKEY", null),
    ],
];
