<?php

namespace api\common\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\filters\AccessControl;

/**
 * Controller for AccessToken model
 */
class AccessTokenController extends ApiController
{

    public $modelClass = 'api\common\models\AccessToken';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['except'] = ['create']; // Not uses authentification for create action
        $behaviors['access'] = [
            'class'  => AccessControl::className(),
            'except' => ['create', 'options'],
            'rules'  => [
                [
                    'allow'   => true,
                    'actions' => ['index'],
                    'roles'   => ['access-token.viewAll']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['view'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('access-token.view', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'   => true,
                    'actions' => ['delete-all'],
                    'roles'   => ['access-token.deleteAll']
                ],
                [
                    'allow'         => true,
                    'actions'       => ['delete'],
                    'matchCallback' => function() {
                        return \Yii::$app->user->can('access-token.delete', ['model' => $this->findModel()]);
                    }   
                ]
            ]
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        $verbs = parent::verbs();

        $verbs['delete-all'] = ['DELETE'];

        return $verbs;
    }

    /**
     * @inheritdoc
     */
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
     * Check access for create action
     * Find user by login and password
     * Throw excetion if not found
     * 
     * @throws ForbiddenHttpException
     */
    public function checkAccessForCreate()
    {
        $login = Yii::$app->request->post('login');
        $password = Yii::$app->request->post('password');

        $userClass = Yii::$app->user->identityClass;
        $user = $userClass::findByLoginAndPassword($login, $password);

        if (!$user) {
            throw new ForbiddenHttpException();
        }

        $params = Yii::$app->request->bodyParams;
        $params['idUser'] = $user->id;
        Yii::$app->request->bodyParams = $params;
    }

    /**
     * Remove all tokens except current token of logged user
     * 
     * @return array
     */
    public function actionDeleteAll()
    {
        $modelClass = $this->modelClass;

        $currentToken = \Yii::$app->user->identity->currentAccessToken;

        $query = $modelClass::find()
                ->select('id')
                ->where('id <> :id', [':id' => $currentToken->id])
                ->indexBy('id')
                ->asArray();

        $exist = array_keys($query->all());
        $modelClass::deleteAll('id <> :id', [':id' => $currentToken->id]);
        $notRemoved = $query->all();

        return array_diff($exist, $notRemoved);
    }

}
