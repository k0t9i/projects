<?php

namespace api\common\models;

use Yii;
use api\components\Filterable;
use yii\db\ActiveRecord;
use api\rbac\HasOwnerInterface;

/**
 * This is the model class for table "{{%user_group}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $mainRole
 *
 * @property User[] $users User relation
 */
class UserGroup extends ActiveRecord implements HasOwnerInterface, Filterable
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group}}';
    }

    /**
     * User relation
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'idUser'])
                        ->viaTable(User::JUNCTION_USER_GROUP, ['idUserGroup' => 'id']);
    }

    /**
     * Get role from AuthManager by mainRole 
     * 
     * @return \yii\rbac\Role|null
     */
    public function getMainRole()
    {
        return Yii::$app->authManager->getRole($this->mainRole);
    }

    /**
     * Get AuthItem active query from permissions of main role
     * 
     * @return \yii\db\ActiveQuery
     */
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
            'id'       => 'id',
            'name'     => 'name',
            'mainRole' => 'mainRole'
        ];
    }

    /**
     * @inheritdoc
     */
    public function isOwner($userId)
    {
        return $this->getUsers()->andWhere([
                    User::tableName() . '.id' => (int) $userId
                ])->exists();
    }

    /**
     * @inheritdoc
     */
    public function getFilterFields()
    {
        return ['name', 'mainRole'];
    }

}
