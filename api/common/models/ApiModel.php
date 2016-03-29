<?php

namespace api\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Base class for all api models
 */
class ApiModel extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'labels' => function ($model) {
                return $model->attributeLabels();
            },
            'hints' => function ($model) {
                return $model->attributeHints();
            }
        ];
    }
}
