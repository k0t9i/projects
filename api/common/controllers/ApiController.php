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
    
    public function actions() {
        $actions = parent::actions();
        
        $actions['index']['prepareDataProvider'] = function () {
            return $this->prepareDataProvider();
        };
        
        return $actions;
    }
    
    protected function prepareDataProvider(\yii\db\ActiveQuery $query = null)
    {    
        $modelClass = $query ? $query->modelClass : $this->modelClass;
        $model = new $modelClass();
        if ($model instanceof \api\common\models\Filterable) {
            $params = json_decode(\Yii::$app->request->get('filter'), true);
            $model->scenario = $modelClass::SCENARIO_FILTER;
            $model->load($params, '');
            
            $dp = $model->search($query);
        } else {
            $dp = new \yii\data\ActiveDataProvider([
                'query' => $query ? $query : $model->find()
            ]);
        }
        return $dp;
    }
}
