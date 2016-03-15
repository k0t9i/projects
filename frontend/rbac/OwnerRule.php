<?php

namespace frontend\rbac;

use yii\rbac\Rule;

class OwnerRule extends Rule
{

    public $name = 'isOwner';

    public function execute($user, $item, $params)
    {
        if (isset($params['model']) && $params['model'] instanceof HasOwnerInterface) {
            return $params['model']->getOwnerId() == $user;
        }
        
        throw new \InvalidArgumentException('Params must have model attribute and model attribute must be instance of HasOwnerInterface');
    }

}
