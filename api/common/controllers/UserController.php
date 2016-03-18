<?php

namespace api\common\controllers;

use api\common\models\User;
use yii\filters\AccessControl;

class UserController extends ApiController
{
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => ['options', 'self'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['user.viewAll']
                ],
                [
                    'allow' => true,
                    'actions' => ['view'],
                    'roles' => ['user.view']
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['user.delete']
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'roles' => ['user.update']
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['user.create']
                ]
            ]
        ];

        return $behaviors;
    }

    public function verbs()
    {
        $verbs = parent::verbs();
        
        $verbs['self'] = ['GET'];
        
        return $verbs;
    }
    
    public function init()
    {
        $this->modelClass = User::className();
    }
    
    public function actionSelf()
    {
        return \Yii::$app->user->identity;
    }
    
    public function actions()
    {
        $actions = parent::actions();

        $actions['create']['scenario'] = User::SCENARIO_CREATE;

        return $actions;
    }

}
