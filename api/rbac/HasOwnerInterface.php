<?php

namespace api\rbac;


interface HasOwnerInterface
{
    public function getOwnerId();
}
