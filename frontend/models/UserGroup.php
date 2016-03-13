<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%user_group}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $main_role
 *
 * @property User[] $users
 * @property \yii\rbac\Role|null $mainRole
 */
class UserGroup extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'main_role'], 'required'],
            [['name'], 'string', 'max' => 1024],
            [['main_role'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('frontend', 'ID'),
            'name' => Yii::t('frontend', 'Name'),
            'main_role' => Yii::t('frontend', 'Main Role'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])->viaTable('{{%j_user_user_group}}', ['id_user_group' => 'id']);
    }

    /**
     * @return \yii\rbac\Role|null
     */
    public function getMainRole()
    {
        return Yii::$app->authManager->getRole($this->main_role);
    }
    
    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'mainRole' => 'main_role'
        ];
    }

}