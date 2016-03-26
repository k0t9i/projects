<?php

namespace api\common\models;

use Yii;

/**
 * This is the model class for table "{{%d_gender}}".
 *
 * @property integer $id
 * @property string $name
 * 
 * @property-read string $localizedName
 */
class DGender extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%d_gender}}';
    }

    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'localizedName'
        ];
    }
    
    public function getLocalizedName()
    {
        return Yii::t('api', $this->name);
    }

}
