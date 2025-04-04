<?php
return [
    /**
     * ---------------------------------------------------------
     * Logging configurations
     * ---------------------------------------------------------
     */
    'default' => 'single',

    'level' => 'info',

    'single' => [
        'path' => logs_dir() . DS . 'app.log',
    ],

    'daily' => [
        'path' => logs_dir(),
    ]
];