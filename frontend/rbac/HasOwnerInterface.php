<?php

namespace frontend\rbac;


interface HasOwnerInterface
{
    public function getOwnerId();
}
