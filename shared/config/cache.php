<?php

return [
    /**
     * ---------------------------------------------------------
     * Current cache settings
     * ---------------------------------------------------------
     *
     * Current configuration to use.
     */
    'current' => 'file',
    /**
     * ---------------------------------------------------------
     * Cache Connections
     * ---------------------------------------------------------
     *
     * Configurations for each cache driver.
     */
    'file' => [
        'params' => [
            'prefix' => str_replace(' ', '', env('APP_NAME')),
            'path' => base_dir() . DS . 'cache' . DS . 'data',
            'ttl' => 600
        ]
    ],
    'database' => [
        'params' => [
            'prefix' => str_replace(' ', '', env('APP_NAME')),
            'table' => 'cache',
            'ttl' => 60
        ]
    ],
    'memcached' => [
        'params' => [
            'prefix' => str_replace(' ', '', env('APP_NAME')),
            'host' => '127.0.0.1',
            'port' => 11211,
            'ttl' => 60
        ]
    ],
    'redis' => [
        'params' => [
            'prefix' => str_replace(' ', '', env('APP_NAME')),
            'host' => '127.0.0.1',
            'port' => 6379,
            'ttl' => 60
        ]
    ]
];

