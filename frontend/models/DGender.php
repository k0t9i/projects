<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%d_gender}}".
 *
 * @property integer $id
 * @property string $name
 *
 * @property User[] $users
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 256],
            [['name'], 'unique']
        ];
    }
}
