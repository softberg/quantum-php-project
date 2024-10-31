<?php

return [
    'current' => 'mysql',
    'mysql' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'database',
        'username' => 'username',
        'password' => 'password',
        'charset' => 'charset',
        'orm' => \Quantum\Libraries\Database\Idiorm\IdiormDbal::class
    ],
    'sqlite' => [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'orm' => \Quantum\Libraries\Database\Idiorm\IdiormDbal::class
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
        'orm' => \Quantum\Libraries\Database\Sleekdb\SleekDbal::class
    ]
];