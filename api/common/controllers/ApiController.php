<?php

namespace api\common\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\CompositeAuth;
use yii\rest\Action;
use api\components\Filterable;
use api\components\FilterQueryBuilder;
use yii\data\ActiveDataProvider;

class ApiController extends ActiveController
{
    public $filterParam = 'filter';
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
        $query = $query ? $query : $model->find();
        if ($model instanceof Filterable) {
            $rawFilter = \Yii::$app->request->get($this->filterParam);
            if ($rawFilter) {
                $filters = json_decode($rawFilter, true);
                if (is_null($filters)) {
                    throw new \InvalidArgumentException("Invalid json in filters");
                }
                $query = FilterQueryBuilder::build($filters, $query);
            }
        }
        $dp = new ActiveDataProvider([
            'query' => $query
        ]);
        return $dp;
    }
}
