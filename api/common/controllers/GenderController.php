<?php

namespace api\common\controllers;

class GenderController extends ApiController
{

    public $modelClass = 'api\common\models\DGender';

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }

}
