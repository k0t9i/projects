<?php

$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'api',
    'language' => 'en',
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
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'application/xml' => 'yii\web\XmlParser'
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
                        'GET self' => 'self',
                        'OPTIONS self' => 'options'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/access-token'],
                    'extraPatterns' => [
                        'DELETE delete-all' => 'delete-all',
                        'OPTIONS delete-all' => 'options'
                    ]
                ],
                'GET,HEAD v1/projects/<id:\d+>/users' => 'v1/project/users',
                'OPTIONS v1/projects/<id:\d+>/users' => 'v1/project/options',
                'POST v1/projects/<idProject:\d+>/users/<idUser:\d+>' => 'v1/project-user/create',
                'OPTIONS v1/projects/<idProject:\d+>/users/<idUser:\d+>' => 'v1/project-user/options',
                'DELETE v1/projects/<idUser:\d+>/users/<idProject:\d+>' => 'v1/project-user/delete',
                'OPTIONS v1/projects/<idUser:\d+>/users/<idProject:\d+>' => 'v1/project-user/options',
                'GET,HEAD v1/users/<id:\d+>/projects' => 'v1/user/projects',
                'OPTIONS v1/users/<id:\d+>/projects' => 'v1/user/options',
                'POST v1/users/<idUser:\d+>/projects/<idProject:\d+>' => 'v1/project-user/create',
                'OPTIONS v1/users/<idUser:\d+>/projects/<idProject:\d+>' => 'v1/project-user/options',
                'DELETE v1/users/<idUser:\d+>/projects/<idProject:\d+>' => 'v1/project-user/delete',
                'OPTIONS v1/users/<idUser:\d+>/projects/<idProject:\d+>' => 'v1/project-user/options',
                'GET,HEAD v1/users/<id:\d+>/user-groups' => 'v1/user/user-groups',
                'OPTIONS v1/users/<id:\d+>/user-groups' => 'v1/user/options',
                'GET,HEAD v1/users/<id:\d+>/permissions' => 'v1/user/permissions',
                'OPTIONS v1/users/<id:\d+>/permissions' => 'v1/user/options',
                'GET,HEAD v1/user-groups/<id:\d+>/users' => 'v1/user-group/users',
                'OPTIONS v1/user-groups/<id:\d+>/users' => 'v1/user-group/options',
                'GET,HEAD v1/user-groups/<id:\d+>/permissions' => 'v1/user-group/permissions',
                'OPTIONS v1/user-groups/<id:\d+>/permissions' => 'v1/user-group/options',
                'GET,HEAD v1/auth-items/roles' => 'v1/auth-item/roles',
                'OPTIONS v1/auth-items/roles' => 'v1/auth-item/options',
                'GET,HEAD v1/auth-items/permissions' => 'v1/auth-item/permissions',
                'OPTIONS v1/auth-items/permissions' => 'v1/auth-item/options',
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
