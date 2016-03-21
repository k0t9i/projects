<?php

namespace api\common\controllers;

use yii\data\ActiveDataProvider;

class UserGroupController extends ApiController
{

    public $modelClass = 'api\common\models\UserGroup';
    
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }
    
    public function verbs()
    {
        $verbs = parent::verbs();
        
        $verbs['users'] = ['GET'];
        
        return $verbs;
    }
    
    public function actionUsers()
    {
        return new ActiveDataProvider([
            'query' => $this->findModel()->getUsers()
        ]);
    }

}
