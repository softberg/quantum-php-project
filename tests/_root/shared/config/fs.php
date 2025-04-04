<?php

return [
    'default' => 'local',

    'dropbox' => [
        'service' => Shared\Services\TokenService::class,
        'params' => [
            'app_key' => '',
            'app_secret' => '',
        ]
    ],

    'gdrive' => [
        'service' => Shared\Services\TokenService::class,
        'params' => [
            'app_key' => '',
            'app_secret' => '',
        ]
    ]
];
