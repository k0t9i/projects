<?php

namespace api\common\controllers;

use yii\filters\AccessControl;

class UserGroupController extends ApiController
{

    public $modelClass = 'api\common\models\UserGroup';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => ['options', 'view', 'index'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['permissions'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('user-group.permissions', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['users'],
                    'roles' => ['user-group.users']
                ]
            ]
        ];

        return $behaviors;
    }
    
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }
    
    public function verbs()
    {
        $verbs = parent::verbs();
        
        $verbs['users'] = ['GET'];
        
        return $verbs;
    }
    
    public function actionUsers()
    {
        return $this->prepareDataProvider($this->findModel()->getUsers());
    }

    public function actionPermissions()
    {
        return $this->prepareDataProvider($this->findModel()->getPermissions());
    }

}
