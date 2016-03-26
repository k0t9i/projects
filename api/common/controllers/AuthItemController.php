<?php

namespace api\common\controllers;

/**
 * Controller for AuthItem model
 */
class AuthItemController extends ApiController
{

    public $modelClass = 'api\common\models\AuthItem';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }

    /**
     * List of 'role type' AuthItem
     * 
     * @return yii\data\ActiveDataProvider
     */
    public function actionRoles()
    {
        $modelClass = $this->modelClass;
        return $this->prepareDataProvider($modelClass::find()->roles());
    }

    /**
     * List of 'permission type' AuthItem
     * 
     * @return yii\data\ActiveDataProvider
     */
    public function actionPermissions()
    {
        $modelClass = $this->modelClass;
        return $this->prepareDataProvider($modelClass::find()->permissions());
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        $verbs = parent::verbs();

        $verbs['roles'] = ['GET'];
        $verbs['permissions'] = ['GET'];

        return $verbs;
    }

}
