<?php

return [
    'current' => 'smtp',

    'mail_trap' => true,

    'smtp' => [
        'host' => '127.0.0.1',
        'secure' => 'ssl',
        'port' => '80',
        'username' => 'test',
        'password' => 'test'
    ],

    'sendinblue' => [
        'api_key' => 'sendinblue_api_key',
    ],

    'sendgrid' => [
        'api_key' => 'sendgrid_api_key',
    ],

    'mandrill' => [
        'api_key' => 'mandrill_api_key'
    ],

    'mailgun' => [
        'api_key' => 'mailgun_api_key',
        'domain' => 'mailgun_domain',
    ],
];
