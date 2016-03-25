<?php

namespace api\common\controllers;

use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class ProjectController extends ApiController
{
    public $modelClass = 'api\common\models\Project';
            
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => ['options'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['project.viewAll']
                ],
                [
                    'allow' => true,
                    'actions' => ['view'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('project.view', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'roles' => ['project.delete']
                ],
                [
                    'allow' => true,
                    'actions' => ['update'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('project.update', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['project.create']
                ],
                [
                    'allow' => true,
                    'actions' => ['users'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('project.users', ['model' => $this->findModel()]);
                    }
                ],
            ]
        ];

        return $behaviors;
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

}
