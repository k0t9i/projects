<?php

namespace api\rbac;


interface HasOwnerInterface
{
    public function isOwner($userId);
}
