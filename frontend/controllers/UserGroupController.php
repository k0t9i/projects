<?php

namespace frontend\controllers;

use yii\rest\ActiveController;
use frontend\models\UserGroup;
use frontend\models\User;
use yii\filters\auth\HttpBasicAuth;

class UserGroupController extends ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => function ($username, $password) {
                return User::findByLoginAndPassword($username, $password);
            }
        ];
        return $behaviors;
    }

    public function init()
    {
        $this->modelClass = UserGroup::className();
    }

}
