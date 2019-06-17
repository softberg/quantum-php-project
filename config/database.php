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
        'driver' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'database',
        'username' => 'username',
        'password' => 'password',
        'charset' => 'charset',
    ),
    'sqlite' => array(
        'driver' => 'sqlite',
        'database' => 'database.sqlite',
        'prefix' => '',
    ),
];

