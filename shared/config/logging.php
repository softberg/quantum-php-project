<?php
return [
    /**
     * ---------------------------------------------------------
     * Logging configurations
     * ---------------------------------------------------------
     */
    'current' => 'single',

    'level' => 'info',

    'single' => [
        'path' => logs_dir() . DS . 'app.log',
    ],

    'daily' => [
        'path' => logs_dir(),
    ]
];