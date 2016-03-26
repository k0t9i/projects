<?php

namespace api\rbac;

use yii\rbac\Rule;

/**
 * "isOwner" auth rule
 * Params must have "model" attribute and "model" attribute must be instance of HasOwnerInterface
 * Rule call HasOwnerInterface::isOwner method
 * 
 */
class OwnerRule extends Rule
{

    public $name = 'isOwner';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model']) && $params['model'] instanceof HasOwnerInterface) {
            return $params['model']->isOwner($user);
        }

        throw new \InvalidArgumentException('Params must have "model" attribute and "model" attribute must be instance of HasOwnerInterface');
    }

}
