<?php

namespace api\common\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\web\NotFoundHttpException;

/**
 * Controller for ProjectUser model
 */
class ProjectUserController extends ApiController
{

    public $modelClass = 'api\common\models\ProjectUser';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class'  => AccessControl::className(),
            'except' => ['options'],
            'rules'  => [
                [
                    'allow'         => true,
                    'actions'       => ['create'],
                    'matchCallback' => function () {
                        return \Yii::$app->user->can('project-user.create', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'         => true,
                    'actions'       => ['delete'],
                    'matchCallback' => function () {
                        return \Yii::$app->user->can('project-user.delete', ['model' => $this->findModel()]);
                    }
                ],
                [
                    'allow'         => true,
                    'actions'       => ['enable', 'disable', 'switch-state'],
                    'matchCallback' => function () {
                        return \Yii::$app->user->can('project-user.switchState', ['model' => $this->findModel()]);
                    }
                ]
            ]
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['index']);
        unset($actions['view']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }

    /**
     * Wrapper over @see yii\rest\CreateAction
     *
     * @param integer $idUser
     * @param integer $idProject
     * @return mixed
     */
    public function actionCreate($idUser, $idProject)
    {
        $action = new CreateAction('create', $this, [
            'modelClass' => $this->modelClass
        ]);

        $params = Yii::$app->request->bodyParams;
        $params['idUser'] = $idUser;
        $params['idProject'] = $idProject;
        Yii::$app->request->bodyParams = $params;

        return $action->run();
    }

    /**
     * Wrapper over @see yii\rest\DeleteAction
     *
     * @param integer $idUser
     * @param integer $idProject
     * @return mixed
     */
    public function actionDelete($idUser, $idProject)
    {
        $action = new DeleteAction('delete', $this, [
            'modelClass' => $this->modelClass
        ]);

        $action->findModel = function () {
            return $this->findModel();
        };

        return $action->run(0);
    }

    /**
     * Disable project user
     *
     * @return array
     */
    public function actionDisable($idUser, $idProject)
    {
        $model = $this->findModel();
        $model->isActive = false;

        return [
            'success' => (boolean)$model->save(false, ['isActive'])
        ];
    }

    /**
     * Enable project user
     *
     * @return array
     */
    public function actionEnable($idUser, $idProject)
    {
        $model = $this->findModel();
        $model->isActive = true;

        return [
            'success' => (boolean)$model->save(false, ['isActive'])
        ];
    }

    /**
     * Switch project user's state
     *
     * @return array
     */
    public function actionSwitchState($idUser, $idProject)
    {
        $model = $this->findModel();
        $model->isActive = !$model->isActive;

        return [
            'success' => (boolean)$model->save(false, ['isActive'])
        ];
    }

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        $verbs = parent::verbs();

        $verbs['enable'] = ['GET'];
        $verbs['disable'] = ['GET'];
        $verbs['switch-state'] = ['GET'];

        return $verbs;
    }

    protected function findModel()
    {
        $idUser = Yii::$app->request->get('idUser');
        $idProject = Yii::$app->request->get('idProject');

        $modelClass = $this->modelClass;
        $model = $modelClass::findByUserAndProject($idUser, $idProject);
        if (!$model) {
            throw new NotFoundHttpException('Project user with idUser="' . $idUser . '" and idProject="' . $idProject . '" not found');
        }

        return $model;
    }

}
