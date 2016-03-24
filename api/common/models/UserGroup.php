<?php

namespace api\common\models;

use Yii;
use api\components\Filterable;

/**
 * This is the model class for table "{{%user_group}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $mainRole
 *
 * @property User[] $users
 */
class UserGroup extends \yii\db\ActiveRecord implements \api\rbac\HasOwnerInterface, Filterable
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
        return $this->hasMany(User::className(), ['id' => 'idUser'])
                ->viaTable(User::JUNCTION_USER_GROUP, ['idUserGroup' => 'id']);
    }

    /**
     * @return \yii\rbac\Role|null
     */
    public function getMainRole()
    {
        return Yii::$app->authManager->getRole($this->mainRole);
    }
    
    public function getPermissions()
    {
        $names = array_keys(Yii::$app->authManager->getPermissionsByRole($this->mainRole));
        
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
            'mainRole' => 'mainRole'
        ];
    }

    public function isOwner($userId)
    {
        return $this->getUsers()->andWhere([
            User::tableName() . '.id' => (int) $userId
        ])->exists();
    }

    public function getFilterFields()
    {
        return ['name', 'mainRole'];
    }

}
