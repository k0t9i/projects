<?php

namespace api\common\controllers;

use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

class AccessTokenController extends ApiController
{

    public $modelClass = 'api\common\models\AccessToken';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['create'];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => ['create', 'options'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index'],
                    'roles' => ['access-token.viewAll']
                ],
                [
                    'allow' => true,
                    'actions' => ['view'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('access-token.view', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow' => true,
                    'actions' => ['delete-all'],
                    'roles' => ['access-token.deleteAll']
                ],
                [
                    'allow' => true,
                    'actions' => ['delete'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('access-token.delete', ['model' => $this->findModel()]);
                    }
                ]
            ]
        ];

        return $behaviors;
    }

    public function verbs()
    {
        $verbs = parent::verbs();

        $verbs['delete-all'] = ['DELETE'];

        return $verbs;
    }

    public function actions()
    {
        $actions = parent::actions();
        $modelClass = $this->modelClass;

        unset($actions['update']);

        $actions['create']['checkAccess'] = [$this, 'checkAccessForCreate'];
        $actions['create']['scenario'] = $modelClass::SCENARIO_CREATE;

        return $actions;
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function checkAccessForCreate()
    {
        $login = \Yii::$app->request->post('login');
        $password = \Yii::$app->request->post('password');

        $userClass = \Yii::$app->user->identityClass;
        $user = $userClass::findByLoginAndPassword($login, $password);

        if (!$user) {
            throw new ForbiddenHttpException();
        }

        $params = \Yii::$app->request->bodyParams;
        $params['idUser'] = $user->id;
        \Yii::$app->request->bodyParams = $params;
    }

    public function actionDeleteAll()
    {
        $modelClass = $this->modelClass;

        $currentToken = \Yii::$app->user->identity->currentAccessToken;

        $query = $modelClass::find()
                ->select('id')
                ->where('id <> :id', [':id' => $currentToken->id])
                ->indexBy('id')
                ->asArray();

        $exist = $query->all();
        $modelClass::deleteAll('id <> :id', [':id' => $currentToken->id]);
        $removed = $query->all();

        return array_diff($exist, $removed);
    }

}
