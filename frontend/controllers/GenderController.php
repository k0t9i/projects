<?php

namespace frontend\controllers;

use frontend\models\DGender;

class GenderController extends ApiController
{

    public function init()
    {
        $this->modelClass = DGender::className();
    }
    
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        
        return $actions;
    }

}
