<?php

return [
    /**
     * ---------------------------------------------------------
     * Base URL of the project
     * ---------------------------------------------------------
     *
     * It will be auto-detected in a web environment if set to null
     */
    'base_url' => null,

    /**
     * ---------------------------------------------------------
     * App name
     * ---------------------------------------------------------
     */
    'app_name' => 'Quantum PHP App',

    /**
     * ---------------------------------------------------------
     * App email
     * ---------------------------------------------------------
     */
    'app_email' => 'app@quantumphp.io',

    /**
     * ---------------------------------------------------------
     * Language
     * ---------------------------------------------------------
     *
     * Possible languages and project default language.
     */
    'multilang' => true,
    'langs' => ['en', 'ru', 'am'],
    'lang_default' => 'en',
    'lang_segment' => 1,

    /**
     * ---------------------------------------------------------
     * Error handling
     * ---------------------------------------------------------
     *
     * Enabling or disabling project debug.
     */
    'debug' => env('DEBUG', true),

    /**
     * ---------------------------------------------------------
     * Two-factor authentication
     * ---------------------------------------------------------
     * 
     * Enables or disables 2-factor authentication
     */
    '2FA' => env('2FA', true),

      /**
       * ---------------------------------------------------------
       * OTP expiration
       * ---------------------------------------------------------
       *
       * OTP expires after minutes defined
       */
    'otp_expires' => 2

];
