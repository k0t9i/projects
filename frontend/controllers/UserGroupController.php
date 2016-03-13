<?php

namespace frontend\controllers;

use frontend\models\UserGroup;

class UserGroupController extends ApiController
{

    public function init()
    {
        $this->modelClass = UserGroup::className();
    }

}
