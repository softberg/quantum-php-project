<?php

return array(
    'current' => 'mysql',
    'mysql' => array(
        'driver' => getenv("DB_DRIVER"),
        'host' => getenv("DB_HOST"),
        'dbname' => getenv("DB_NAME"),
        'username' => getenv("DB_USERNAME"),
        'password' => getenv("DB_PASSWORD"),
    ),
    'sqlite' => array(
        'driver' => 'sqlite',
        'database' => 'database.sqlite',
        'prefix' => '',
    ),
);
