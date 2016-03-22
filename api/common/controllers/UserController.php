<?php

namespace api\common\controllers;

use yii\filters\AccessControl;

class UserController extends ApiController
{
    
    public $modelClass = 'api\common\models\User';
    
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => ['options', 'self', 'projects'],
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
        $verbs['projects'] = ['GET'];
        
        return $verbs;
    }
    
    public function actionSelf()
    {
        return \Yii::$app->user->identity;
    }
    
    public function actions()
    {
        $actions = parent::actions();
        $modelClass = $this->modelClass;
        
        $actions['create']['scenario'] = $modelClass::SCENARIO_CREATE;

        return $actions;
    }
    
    public function actionProjects()
    {
        return $this->prepareDataProvider($this->findModel()->getProjects());
    }

}
