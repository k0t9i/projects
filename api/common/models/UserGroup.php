<?php

namespace api\common\models;

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
class UserGroup extends \yii\db\ActiveRecord implements \api\rbac\HasOwnerInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])
                ->viaTable(User::JUNCTION_USER_GROUP, ['id_user_group' => 'id']);
    }

    /**
     * @return \yii\rbac\Role|null
     */
    public function getMainRole()
    {
        return Yii::$app->authManager->getRole($this->main_role);
    }
    
    public function getPermissions()
    {
        $names = array_keys(Yii::$app->authManager->getPermissionsByRole($this->main_role));
        
        return AuthItem::find()->permissions()->andWhere([
            'name' => $names
        ]);
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

    public function isOwner($userId)
    {
        return $this->getUsers()->andWhere([
            User::tableName() . '.id' => (int) $userId
        ])->exists();
    }

}
