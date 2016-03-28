<?php
return [
    'language' => 'en-US',
    'components' => [
        'db' => [
            'dsn' => 'pgsql:host=localhost;dbname=test_project_db',
            'username' => 'postgres',
            'password' => '',
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
    ],
];