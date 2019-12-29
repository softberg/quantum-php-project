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
     * driver     : mysql, pgsql, sqlite
     * host       : The database server (localhost)
     * dbname     : The database name
     * username   : Username of the database server
     * password   : Password of the database server
     * charset    : Default charset
     */
    'mysql' => array(
        'driver' => env("DB_DRIVER", "mysql"),
        'host' => env("DB_HOST", "localhost"),
        'dbname' => env("DB_NAME"),
        'username' => env("DB_USERNAME", "root"),
        'password' => env("DB_PASSWORD"),
        'charset' => env("DB_CHARSET", 'utf8'),
    ),
];
