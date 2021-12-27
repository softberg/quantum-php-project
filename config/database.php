<?php

return [
    /**
     * ---------------------------------------------------------
     * Current database settings
     * ---------------------------------------------------------
     *
     * Current configuration to use.
     */
    'current' => 'mysql',

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
        'driver' => 'sleekdb',
        "auto_cache" => true,
        "cache_lifetime" => null,
        "timeout" => false,
        "primary_key" => "_id",
        "search" => [
            "min_length" => 2,
            "mode" => "or",
            "score_key" => "scoreKey",
            "algorithm" => Query::SEARCH_ALGORITHM["hits"]
        ]
    ]
];

