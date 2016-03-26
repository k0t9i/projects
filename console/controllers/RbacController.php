<?php

namespace console\controllers;

use yii\console\Controller;
use console\models\Rbac;

class RbacController extends Controller
{

    public $defaultAction = 'init';

    /**
     * Name of used db connection
     * 
     * @var string 
     */
    public $db;

    /**
     * Remove all auth items and creates its again
     */
    public function actionInit()
    {
        (new Rbac(['db' => $this->db]))->initRoles();
    }

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return [
            'db'
        ];
    }

}
