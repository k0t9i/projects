<?php

namespace api\common\controllers;

use Yii;
use api\rbac\HasOwnerInterface;
use api\rbac\OwnerRule;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\CompositeAuth;
use yii\rest\Action;
use api\components\Filterable;
use api\components\FilterQueryBuilder;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\filters\Cors;

/**
 * Base api controller
 */
class ApiController extends ActiveController
{

    /**
     * Name of get parameter for model filtering
     *
     * @var string
     */
    public $filterParam = 'filter';

    /**
     *
     * @var yii\rest\Action
     */
    private $_dummyAction;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => ['options'],
            'authMethods' => [
                HttpBearerAuth::className(),
                /**
                 * @todo Remove for production?
                 */
                QueryParamAuth::className()
            ]
        ];

        // corsFilter should be running before authenticator
        $behaviors = array_merge([
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS']
                ],
            ]
        ], $behaviors);
        return $behaviors;
    }

    public function actionLabels()
    {
        $model = new $this->modelClass();

        return $model->attributeLabels();
    }

    /**
     * Wrapper for @see yii\rest\Action::findModel
     * Find controller model by 'id' get parameter
     *
     * @return yii\db\ActiveRecord|null
     */
    protected function findModel()
    {
        if (!$this->_dummyAction) {
            $this->_dummyAction = new Action('__dummy__', $this, [
                'modelClass' => $this->modelClass
            ]);
        } else {
            $this->_dummyAction->modelClass = $this->modelClass;
        }
        return $this->_dummyAction->findModel(\Yii::$app->request->get('id'));
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        /**
         * All index actions can be filtered
         */
        $actions['index']['prepareDataProvider'] = function () {
            return $this->prepareDataProvider();
        };

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        $verbs = parent::verbs();

        $verbs['labels'] = ['GET'];

        return $verbs;
    }

    /**
     * Apply filters for ActiveDataProviders of Filterable model
     *
     * @param ActiveQuery $query
     * @return ActiveDataProvider
     * @throws \InvalidArgumentException
     */
    protected function prepareDataProvider(ActiveQuery $query = null)
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

    /**
     * Check if user is owner of model
     * @see OwnerRule
     *
     * @param integer $id
     * @return array
     * @throws NotSupportedException
     */
    public function actionIsOwner($id)
    {
        $model = $this->findModel();
        if ($model instanceof HasOwnerInterface) {
            return [
                'result' => $model->isOwner(Yii::$app->user->getId()) ? 1 : 0
            ];
        } else {
            throw new NotSupportedException();
        }
    }

}
