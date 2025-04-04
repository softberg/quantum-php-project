<?php
return [
    'default' => 'single',

    'single' => [
        'path' => logs_dir() . DS . 'app.log',
        'level' => 'info',
    ],

    'daily' => [
        'path' => logs_dir(),
        'level' => 'warning',
    ]
];