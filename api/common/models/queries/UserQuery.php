<?php

namespace api\common\models\queries;

use yii\db\ActiveQuery;

/**
 * Custom ActiveQuery class for User model
 */
class UserQuery extends ActiveQuery
{

    /**
     * Only active users
     * 
     * @return \api\common\models\queries\UserQuery
     */
    public function active()
    {
        $this->andWhere([
            'isActive' => true
        ]);
        return $this;
    }

}
