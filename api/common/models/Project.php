<?php

namespace api\common\models;

use Yii;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $startedAt
 * @property integer $endedAt
 * @property boolean $isActive
 * 
 * @property-read ProjectUser[] $projectUsers
 * @property-read User[] $users
 */
class Project extends \yii\db\ActiveRecord implements \api\rbac\HasOwnerInterface, Filterable
{
    use ActiveRecordFilterTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::className(), ['idProject' => 'id']);
    }
    
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'id_User'])
                ->via('projectUsers');
    }

    public function isOwner($userId)
    {
        $ids = $this->getProjectUsers()
                ->select('idUser')
                ->where(['isActive' => true])
                ->indexBy('idUser')
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
                return Yii::$app->formatter->format($model->startedAt, 'datetime');
            },
            'ednedAt' => function($model) {
                return Yii::$app->formatter->format($model->endedAt, 'datetime');
            },
            'isActive' => 'isActive'
        ];
    }

    protected function filterField()
    {
        return [
            'name'
        ];
    }

}
