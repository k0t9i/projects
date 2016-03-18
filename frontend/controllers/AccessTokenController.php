<?php

namespace frontend\controllers;

use frontend\models\AccessToken;
use yii\web\ForbiddenHttpException;
use frontend\models\User;
use yii\filters\AccessControl;

class AccessTokenController extends ApiController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['create'];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'except' => ['create', 'options', 'self'],
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

    public function init()
    {
        $this->modelClass = AccessToken::className();
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['update']);

        $actions['create']['checkAccess'] = [$this, 'checkAccessForCreate'];
        $actions['create']['scenario'] = AccessToken::SCENARIO_CREATE;

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
        AccessToken::deleteAll('id <> :id', [':id' => $currentToken->id]);
        $removed = $query->all();
      
        return array_diff($exist, $removed);
    }
    
    public function actionSelf()
    {
        $identity = \Yii::$app->user->identity;
        return $identity ? $identity->currentAccessToken : null;
    }

}
