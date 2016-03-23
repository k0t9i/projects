<?php

namespace api\common\models\queries;

class UserQuery extends \yii\db\ActiveQuery
{

    public function active()
    {
        $this->andWhere([
            'isActive' => true
        ]);
        return $this;
    }

}
