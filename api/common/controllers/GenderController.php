<?php

namespace api\common\controllers;

/**
 * Controller for DGender model
 */
class GenderController extends ApiController
{

    public $modelClass = 'api\common\models\DGender';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);

        return $actions;
    }

}
