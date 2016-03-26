<?php

namespace api\common\models\queries;

use api\common\models\AuthItem;
use yii\db\ActiveQuery;

/**
 * Custom ActiveQuery class for AuthItem model
 */
class AuthItemQuery extends ActiveQuery
{

    /**
     * Only role type
     * 
     * @return \api\common\models\queries\AuthItemQuery
     */
    public function roles()
    {
        $this->andWhere([
            'type' => AuthItem::TYPE_ROLE
        ]);
        return $this;
    }

    /**
     * Only permission type
     * 
     * @return \api\common\models\queries\AuthItemQuery
     */
    public function permissions()
    {
        $this->andWhere([
            'type' => AuthItem::TYPE_PERMISSION
        ]);
        return $this;
    }

}
