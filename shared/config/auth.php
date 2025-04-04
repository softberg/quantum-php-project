<?php

return [
    /**
     * ---------------------------------------------------------
     * Authentication configurations
     * ---------------------------------------------------------
     */
    'default' => 'session',

    'session' => [
        'service' => Shared\Services\AuthService::class
    ],

    'jwt' => [
        'service' => Shared\Services\AuthService::class,
        'claims' => [
            'jti' => uniqid(),
            'iss' => 'issuer',
            'aud' => 'audience',
            'iat' => time(),
            'nbf' => time() + 1,
            'exp' => time() + 3600 // 1 hour
        ]
    ]
];
