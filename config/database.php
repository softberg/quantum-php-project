<?php

return [
    /**
     * ---------------------------------------------------------
     * Current database settings
     * ---------------------------------------------------------
     *
     * Current configuration to use.
     */
    'current' => 'sleekdb',

    /**
     * ---------------------------------------------------------
     * Database Connections
     * ---------------------------------------------------------
     *
     * You can define as many database configurations as you want.
     *
     * driver     : mysql, pgsql, sqlite, sleekdb
     * host       : The database server (localhost)
     * dbname     : The database name
     * username   : Username of the database server
     * password   : Password of the database server
     * charset    : Default charset
     */
    'mysql' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'database',
        'username' => 'username',
        'password' => 'password',
        'charset' => 'charset',
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
        'database_dir' => base_dir() . DS . 'base' . DS . 'store',
        'orm' => \Quantum\Libraries\Database\Sleekdb\SleekDbal::class
    ]
];

