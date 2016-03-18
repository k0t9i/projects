<?php

namespace api\common\controllers;

use api\common\models\UserGroup;

class UserGroupController extends ApiController
{

    public function init()
    {
        $this->modelClass = UserGroup::className();
    }

}
