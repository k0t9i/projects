<?php

namespace api\rbac;

/**
 * Interface for model which can have OwnerRule
 * @see api\rbac\OwnerRule
 */
interface HasOwnerInterface
{

    /**
     * Check if user is owner of this model
     * 
     * @param integer $userId
     * @return boolean
     */
    public function isOwner($userId);
}
