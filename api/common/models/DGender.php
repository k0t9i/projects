<?php

namespace api\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%d_gender}}".
 *
 * @property integer $id
 * @property string $name
 * 
 * @property-read string $localizedName Localized gender name
 */
class DGender extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%d_gender}}';
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id'   => 'id',
            'name' => 'localizedName'
        ];
    }

    /**
     * Get localized gender name
     * 
     * @return string
     */
    public function getLocalizedName()
    {
        return Yii::t('api', $this->name);
    }

}
