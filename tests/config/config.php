<?php
return [
    'language' => 'en-US',
    'components' => [
        'db' => [
            'dsn' => 'pgsql:host=localhost;dbname=test_projects_db',
            'username' => 'test',
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