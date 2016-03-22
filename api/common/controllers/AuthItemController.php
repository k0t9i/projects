<?php

namespace api\common\controllers;

class AuthItemController extends ApiController
{

    public $modelClass = 'api\common\models\AuthItem';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }
    
    public function actionRoles()
    {
        $modelClass = $this->modelClass;
        return $this->prepareDataProvider($modelClass::find()->roles());
    }
    
    public function actionPermissions()
    {
        $modelClass = $this->modelClass;
        return $this->prepareDataProvider($modelClass::find()->permissions());
    }
    
    public function verbs()
    {
        $verbs = parent::verbs();
        
        $verbs['roles'] = ['GET'];
        $verbs['permissions'] = ['GET'];
        
        return $verbs;
    }

}
