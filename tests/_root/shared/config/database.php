<?php

return [
    'default' => 'sleekdb',

    'mysql' => [
        'driver' => env("DB_DRIVER", "mysql"),
        'host' => env("DB_HOST", "localhost"),
        'dbname' => env("DB_NAME"),
        'username' => env("DB_USERNAME", "root"),
        'password' => env("DB_PASSWORD"),
        'charset' => env("DB_CHARSET", 'utf8'),
    ],
    'sqlite' => [
        'driver' => 'sqlite',
        'database' => 'database.sqlite',
        'prefix' => '',
    ],
    'sleekdb' => [
        'config' => [
            'auto_cache' => true,
            'cache_lifetime' => null,
            'timeout' => false,
            'search' => [
                'min_length' => 2,
                'mode' => 'or',
                'score_key' => 'scoreKey',
                'algorithm' => 1
            ],
        ],
        'database_dir' => base_dir() . DS . 'shared' . DS . 'store',
    ]
];