<?php

return [
    /**
     * ---------------------------------------------------------
     * Session settings
     * ---------------------------------------------------------
     */
    'default' => 'native',

    'native' => [
        'timeout' => 300
    ],

    'database' => [
        'table' => 'sessions',
        'timeout' => 300,
    ]
];