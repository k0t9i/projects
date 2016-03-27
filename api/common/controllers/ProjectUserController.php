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
        
        $action->findModel = function() use ($idUser, $idProject){
            $modelClass = $this->modelClass;
            $model = $modelClass::findByUserAndProject($idUser, $idProject);
            if (!$model) {
                throw new NotFoundHttpException('Project user with idUser="' . $idUser . '" and idProject="' . $idProject . '" not found');
            }
            
            return $model;
        };
        
        return $action->run(0);
    }

}
