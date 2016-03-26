<?php

namespace api\common\controllers;

use yii\filters\AccessControl;

/**
 * Controller for Project model
 */
class UserController extends ApiController
{

    public $modelClass = 'api\common\models\User';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class'  => AccessControl::className(),
            'except' => ['options', 'self'],
            'rules'  => [
                [
                    'allow'   => true,
                    'actions' => ['index'],
                    'roles'   => ['user.viewAll']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['view'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('user.view', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'   => true,
                    'actions' => ['delete'],
                    'roles'   => ['user.delete']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['update'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('user.update', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'   => true,
                    'actions' => ['create'],
                    'roles'   => ['user.create']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['projects'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('user.projects', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'         => true,
                    'actions'       => ['user-groups'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('user.userGroups', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'         => true,
                    'actions'       => ['permissions'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('user.permissions', ['model' => $this->findModel()]);
                    }
                ]
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

        $verbs['self'] = ['GET'];
        $verbs['projects'] = ['GET'];

        return $verbs;
    }

    /**
     * Show self user info
     * Alias for user/view
     * 
     * @return api\common\models\User
     */
    public function actionSelf()
    {
        $parts = ['user', 'view'];
        if ($this->module) {
            array_unshift($parts, $this->module->id);
        }
        $_GET['id'] = \Yii::$app->user->getId();
        return \Yii::$app->runAction(implode('/', $parts), ['id' => \Yii::$app->user->getId()]);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $modelClass = $this->modelClass;

        $actions['create']['scenario'] = $modelClass::SCENARIO_CREATE;

        return $actions;
    }

    /**
     * List user projects
     * 
     * @return yii\data\ActiveDataProvider
     */
    public function actionProjects()
    {
        return $this->prepareDataProvider($this->findModel()->getProjects());
    }

    /**
     * List of user usergroups
     * 
     * @return yii\data\ActiveDataProvider
     */
    public function actionUserGroups()
    {
        return $this->prepareDataProvider($this->findModel()->getUserGroups());
    }

    /**
     * List of user pemissions
     * 
     * @return yii\data\ActiveDataProvider
     */
    public function actionPermissions()
    {
        return $this->prepareDataProvider($this->findModel()->getPermissions());
    }

}
