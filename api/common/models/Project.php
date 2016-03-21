<?php

namespace api\common\models;

use Yii;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $started_at
 * @property integer $ended_at
 * @property boolean $is_active
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'started_at'], 'required'],
            [['description'], 'string'],
            [['started_at', 'ended_at'], 'integer'],
            [['is_active'], 'boolean'],
            [['name'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'started_at' => 'Started At',
            'ended_at' => 'Ended At',
            'is_active' => 'Is Active',
        ];
    }
}
