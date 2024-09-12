<?php

return [
    /**
     * ---------------------------------------------------------
     * Captcha configurations
     * ---------------------------------------------------------
     */
    'current' => 'recaptcha',

    'recaptcha' => [
        'type' => 'avisible',
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],

    'hcaptcha' => [
        'type' => 'invisible',
        'site_key' => env('HCAPTCHA_SITE_KEY'),
        'secret_key' => env('HCAPTCHA_SECRET_KEY'),
    ]
];
