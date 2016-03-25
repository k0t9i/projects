<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'api',
    'language' => 'ru',
    'charset' => 'utf-8',
    'timeZone' => 'Europe/Moscow',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'api\common\models\User',
            'enableSession' => false,
            'loginUrl' => null
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'datetimeFormat' => 'php:c',
            'nullDisplay' => ''
        ],
        'i18n' => [
            'translations' => [
                'api*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@api/messages'
                ]
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => [
                        'v1/user-group', 'v1/gender', 'v1/project', 'v1/auth-item'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/user'],
                    'extraPatterns' => [
                        'GET self' => 'self'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/access-token'],
                    'extraPatterns' => [
                        'DELETE delete-all' => 'delete-all'
                    ]
                ],
                'GET v1/projects/<id:\d+>/users' => 'v1/project/users',
                'GET v1/users/<id:\d+>/projects' => 'v1/user/projects',
                'GET v1/users/<id:\d+>/user-groups' => 'v1/user/user-groups',
                'GET v1/users/<id:\d+>/permissions' => 'v1/user/permissions',
                'GET v1/user-groups/<id:\d+>/users' => 'v1/user-group/users',
                'GET v1/user-groups/<id:\d+>/permissions' => 'v1/user-group/permissions',
                'GET v1/auth-items/roles' => 'v1/auth-item/roles',
                'GET v1/auth-items/permissions' => 'v1/auth-item/permissions',
            ],
        ],
    ],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'params' => $params,
];
