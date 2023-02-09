<?php

return [
    /**
     * ---------------------------------------------------------
     * Current mailer settings
     * ---------------------------------------------------------
     *
     * Current configuration to use.
     */
    'current' => 'sendinblue',

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
];
