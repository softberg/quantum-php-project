<?php

return [
    /*
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
    'app_name' => 'Quantum PHP Framework',

    /**
     * ---------------------------------------------------------
     * App email
     * ---------------------------------------------------------
     */
    'app_email' => 'admin@quanumt.io',

    /**
     * ---------------------------------------------------------
     * Language
     * ---------------------------------------------------------
     *
     * Possible languages and project default language.
     */
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
     * Two step verification
     * ---------------------------------------------------------
     * 
     * Enables or disables 2 step verification
     */
    '2SV' => env('2SV', true),

      /**
       * ---------------------------------------------------------
       * OTP expiration
       * ---------------------------------------------------------
       *
       * OTP expires after minutes defined
       */
    'otp_expires' => 2

];
