<?php

namespace frontend\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\CompositeAuth;

class ApiController extends ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
                QueryParamAuth::className()
            ]
        ];
        return $behaviors;
    }

}
