<?php

return [
    [
        'class'      => 'yii\rest\UrlRule',
        'controller' => [
            'v1/user-group', 'v1/gender', 'v1/project', 'v1/auth-item'
        ]
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['v1/user'],
        'extraPatterns' => [
            'GET self'     => 'self',
            'OPTIONS self' => 'options'
        ]
    ],
    [
        'class'         => 'yii\rest\UrlRule',
        'controller'    => ['v1/access-token'],
        'extraPatterns' => [
            'DELETE delete-all'  => 'delete-all',
            'OPTIONS delete-all' => 'options'
        ]
    ],
    'GET,HEAD v1/projects/<id:\d+>/users'                                  => 'v1/project/users',
    'OPTIONS v1/projects/<id:\d+>/users'                                   => 'v1/project/options',
    'POST v1/projects/<idProject:\d+>/users/<idUser:\d+>'                  => 'v1/project-user/create',
    'OPTIONS v1/projects/<idProject:\d+>/users/<idUser:\d+>'               => 'v1/project-user/options',
    'DELETE v1/projects/<idProject:\d+>/users/<idUser:\d+>'                => 'v1/project-user/delete',
    'OPTIONS v1/projects/<idProject:\d+>/users/<idUser:\d+>'               => 'v1/project-user/options',
    'GET,HEAD v1/projects/<idProject:\d+>/users/<idUser:\d+>/enable'       => 'v1/project-user/enable',
    'OPTIONS v1/projects/<idProject:\d+>/users/<idUser:\d+>/enable'        => 'v1/project-user/options',
    'GET,HEAD v1/projects/<idProject:\d+>/users/<idUser:\d+>/disable'      => 'v1/project-user/disable',
    'OPTIONS v1/projects/<idProject:\d+>/users/<idUser:\d+>/disable'       => 'v1/project-user/options',
    'GET,HEAD v1/projects/<idProject:\d+>/users/<idUser:\d+>/switch-state' => 'v1/project-user/switch-state',
    'OPTIONS v1/projects/<idProject:\d+>/users/<idUser:\d+>/switch-state'  => 'v1/project-user/options',
    'GET,HEAD v1/users/<id:\d+>/projects'                                  => 'v1/user/projects',
    'OPTIONS v1/users/<id:\d+>/projects'                                   => 'v1/user/options',
    'POST v1/users/<idUser:\d+>/projects/<idProject:\d+>'                  => 'v1/project-user/create',
    'DELETE v1/users/<idUser:\d+>/projects/<idProject:\d+>'                => 'v1/project-user/delete',
    'OPTIONS v1/users/<idUser:\d+>/projects/<idProject:\d+>'               => 'v1/project-user/options',
    'GET,HEAD v1/users/<id:\d+>/user-groups'                               => 'v1/user/user-groups',
    'OPTIONS v1/users/<id:\d+>/user-groups'                                => 'v1/user/options',
    'GET,HEAD v1/users/<id:\d+>/permissions'                               => 'v1/user/permissions',
    'OPTIONS v1/users/<id:\d+>/permissions'                                => 'v1/user/options',
    'GET,HEAD v1/users/<id:\d+>/enable'                                    => 'v1/user/enable',
    'OPTIONS v1/users/<id:\d+>/enable'                                     => 'v1/user/options',
    'GET,HEAD v1/users/<id:\d+>/disable'                                   => 'v1/user/disable',
    'OPTIONS v1/users/<id:\d+>/disable'                                    => 'v1/user/options',
    'GET,HEAD v1/users/<id:\d+>/switch-state'                              => 'v1/user/switch-state',
    'OPTIONS v1/users/<id:\d+>/switch-state'                               => 'v1/user/options',
    'GET,HEAD v1/user-groups/<id:\d+>/users'                               => 'v1/user-group/users',
    'OPTIONS v1/user-groups/<id:\d+>/users'                                => 'v1/user-group/options',
    'GET,HEAD v1/user-groups/<id:\d+>/permissions'                         => 'v1/user-group/permissions',
    'OPTIONS v1/user-groups/<id:\d+>/permissions'                          => 'v1/user-group/options',
    'GET,HEAD v1/auth-items/roles'                                         => 'v1/auth-item/roles',
    'OPTIONS v1/auth-items/roles'                                          => 'v1/auth-item/options',
    'GET,HEAD v1/auth-items/permissions'                                   => 'v1/auth-item/permissions',
    'OPTIONS v1/auth-items/permissions'                                    => 'v1/auth-item/options',
];

