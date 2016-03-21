<?php

namespace api\common\controllers;

use yii\filters\AccessControl;

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
                    'roles' => ['project.update']
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['project.create']
                ]
            ]
        ];

        return $behaviors;
    }

}
