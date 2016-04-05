<?php

namespace api\common\controllers;

use yii\filters\AccessControl;

/**
 * Controller for Project model
 */
class ProjectController extends ApiController
{

    public $modelClass = 'api\common\models\Project';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class'  => AccessControl::className(),
            'except' => ['options', 'labels', 'is-owner'],
            'rules'  => [
                [
                    'allow'   => true,
                    'actions' => ['index'],
                    'roles'   => ['project.viewAll']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['view'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('project.view', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'   => true,
                    'actions' => ['delete'],
                    'roles'   => ['project.delete']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['update'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('project.update', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'   => true,
                    'actions' => ['create'],
                    'roles'   => ['project.create']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['users'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('project.users', ['model' => $this->findModel()]);
                    }
                ],
            ]
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        $verbs = parent::verbs();

        $verbs['users'] = ['GET'];

        return $verbs;
    }

    /**
     * List of project users
     * 
     * @return yii\data\ActiveDataProvider
     */
    public function actionUsers()
    {
        return $this->prepareDataProvider($this->findModel()->getUsers());
    }

}
