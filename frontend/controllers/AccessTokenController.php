<?php

namespace frontend\controllers;

use frontend\models\AccessToken;
use yii\web\ForbiddenHttpException;
use frontend\models\User;

class AccessTokenController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        $behaviors['authenticator']['except'] = ['create'];
        
        return $behaviors;
    }

    public function init()
    {
        $this->modelClass = AccessToken::className();
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['index']);
        unset($actions['update']);

        $actions['create']['checkAccess'] = [$this, 'checkAccessForCreate'];

        return $actions;
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function checkAccessForCreate()
    {
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');

        $user = User::findByLoginAndPassword($login, $password);

        if (!$user) {
            throw new ForbiddenHttpException();
        }
        
        $params = \Yii::$app->request->bodyParams;
        $params['id_user'] = $user->id;
        \Yii::$app->request->bodyParams = $params;
    }

}
