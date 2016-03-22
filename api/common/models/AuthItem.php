<?php

namespace api\common\models;

use Yii;
use yii\rbac\Role;
use api\common\models\queries\AuthItemQuery;
use yii\db\ActiveRecord;

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
class AuthItem extends ActiveRecord implements Filterable
{

    use ActiveRecordFilterTrait;

    const TYPE_ROLE = Role::TYPE_ROLE;
    const TYPE_PERMISSION = Role::TYPE_PERMISSION;

    public static function find()
    {
        return new AuthItemQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'name' => 'name',
            'type' => 'type',
            'description' => 'description',
            'createdAt' => function($model) {
                return Yii::$app->formatter->format($model->created_at, 'datetime');
            },
            'updatedAt' => function($model) {
                return Yii::$app->formatter->format($model->updated_at, 'datetime');
            }
        ];
    }

    /**
     * @inheritdoc
     */
    protected function filterField()
    {
        return [
            'name', 'type', 'description'
        ];
    }
    
    protected function prepareFilterQuery(\yii\db\ActiveQuery $query)
    {
        if ($this->name) {
            $query->andWhere(['like', 'name', $this->name]);
        }
        if ($this->type) {
            $query->andWhere(['type' => $this->type]);
        }
        if ($this->description) {
            $query->andWhere(['like', 'description', $this->description]);
        }
    }

}
