<?php

namespace api\common\models;

use Yii;
use yii\rbac\Role;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 */
class AuthItem extends \yii\db\ActiveRecord
{

    const TYPE_ROLE = Role::TYPE_ROLE;
    const TYPE_PERMISSION = Role::TYPE_PERMISSION;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }
    
    public function fields()
    {
        return [
            'name' => 'name',
            'type' => 'type',
            'description' => 'description',
            'createdAt' => function($model) {
                return \Yii::$app->formatter->format($model->created_at, 'datetime');
            },
            'updatedAt' => function($model) {
                return \Yii::$app->formatter->format($model->updated_at, 'datetime');
            }
        ];
    }

}
