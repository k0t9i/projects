<?php

namespace console\controllers;

use yii\console\Controller;
use console\models\Rbac;

class RbacController extends Controller
{

    public $defaultAction = 'init';

    public function actionInit()
    {
        (new Rbac())->initRoles();
    }

}
