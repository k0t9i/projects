<?php

namespace frontend\controllers;

use frontend\models\User;

class UserController extends ApiController
{

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
