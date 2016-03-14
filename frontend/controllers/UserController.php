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

}
