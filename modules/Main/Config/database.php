<?php

return array(
    'current' => 'mysql',
    'mysql' => array(
        'driver' => env("DB_DRIVER", "mysql"),
        'host' => env("DB_HOST", "localhost"),
        'dbname' => env("DB_NAME"),
        'username' => env("DB_USERNAME", "root"),
        'password' => env("DB_PASSWORD"),
        'charset' => env("DB_CHARSET", 'utf8'),
    ),
);
