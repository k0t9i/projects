<?php

namespace console\controllers;

use yii\console\Controller;
use console\models\Rbac;

class RbacController extends Controller
{

    public $defaultAction = 'init';
    public $db;

    public function actionInit()
    {
        (new Rbac(['db' => $this->db]))->initRoles();
    }
    
    public function options($actionID)
    {
        return [
            'db'
        ];
    }

}
