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
 * 
 * @property-read ProjectUser[] $projectUsers
 * @property-read User[] $users
 */
class Project extends \yii\db\ActiveRecord implements \api\rbac\HasOwnerInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::className(), ['id_project' => 'id']);
    }
    
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_user'])
                ->via('projectUsers');
    }

    public function isOwner($userId)
    {
        $ids = $this->getProjectUsers()
                ->select('id_user')
                ->indexBy('id_user')
                ->asArray()
                ->all();
        return array_key_exists($userId, $ids);
    }
    
    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'name' => 'name',
            'description' => 'description',
            'startedAt' => function($model) {
                return Yii::$app->formatter->format($model->started_at, 'datetime');
            },
            'ednedAt' => function($model) {
                return Yii::$app->formatter->format($model->ended_at, 'datetime');
            },
            'isActive' => 'is_active'
        ];
    }

}
