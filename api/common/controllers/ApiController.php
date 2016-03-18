<?php

namespace api\common\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\CompositeAuth;
use yii\rest\Action;

class ApiController extends ActiveController
{

    private $_action;

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

    protected function findModel()
    {
        if (!$this->_action) {
            $this->_action = new Action('__dummy__', $this, [
                'modelClass' => $this->modelClass
            ]);
        } else {
            $this->_action->modelClass = $this->modelClass;
        }
        return $this->_action->findModel(\Yii::$app->request->get('id'));
    }

}
