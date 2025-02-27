<?php

return [
    /**
     * ---------------------------------------------------------
     * Cache settings
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
        'prefix' => str_replace(' ', '', env('APP_NAME')),
        'path' => base_dir() . DS . 'cache' . DS . 'data',
        'ttl' => 600
    ],
    'database' => [
        'prefix' => str_replace(' ', '', env('APP_NAME')),
        'table' => 'cache',
        'ttl' => 60
    ],
    'memcached' => [
        'prefix' => str_replace(' ', '', env('APP_NAME')),
        'host' => '127.0.0.1',
        'port' => 11211,
        'ttl' => 60
    ],
    'redis' => [
        'prefix' => str_replace(' ', '', env('APP_NAME')),
        'host' => '127.0.0.1',
        'port' => 6379,
        'ttl' => 60
    ]
];

