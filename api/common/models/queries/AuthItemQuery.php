<?php

namespace api\common\models\queries;

use api\common\models\AuthItem;

class AuthItemQuery extends \yii\db\ActiveQuery
{
    public function roles()
    {
        $this->andWhere([
            'type' => AuthItem::TYPE_ROLE
        ]);
        return $this;
    }
    
    public function permissions()
    {
        $this->andWhere([
            'type' => AuthItem::TYPE_PERMISSION
        ]);
        return $this;
    }
}
