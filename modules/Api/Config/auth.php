<?php

return [
    /**
     * ---------------------------------------------------------
     * Auth
     * ---------------------------------------------------------
     *
     * Type identifies Auth class to use and can be one of these values: web or api
     * Service identifies the Auth service to use
     */
    'type' => 'api',
    'service' => Base\Services\AuthService::class,
    'claims' => [
        'jti' => uniqid(),
        'iss' => 'issuer',
        'aud' => 'audience',
        'iat' => time(),
        'nbf' => time() + 1,
        'exp' => time() + 300
    ]
];
