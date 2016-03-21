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

}
