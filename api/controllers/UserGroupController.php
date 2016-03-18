<?php

namespace api\controllers;

use api\models\UserGroup;

class UserGroupController extends ApiController
{

    public function init()
    {
        $this->modelClass = UserGroup::className();
    }

}
