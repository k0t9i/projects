<?php

namespace api\common\models;

use Yii;
use api\components\Filterable;
use api\rbac\HasOwnerInterface;

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
 * @property-read ProjectUser[] $projectUsers ProjectUser relation
 * @property-read User[] $users User relation
 */
class Project extends ApiModel implements HasOwnerInterface, Filterable
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * ProjectUser relation
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::className(), ['idProject' => 'id']);
    }

    /**
     * User relation
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'idUser'])
                        ->via('projectUsers');
    }

    /**
     * @inheritdoc
     */
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
            'id'          => 'id',
            'name'        => 'name',
            'description' => 'description',
            'startedAt'   => function($model) {
                return Yii::$app->formatter->format($model->startedAt, 'datetime');
            },
            'endedAt' => function($model) {
                return Yii::$app->formatter->format($model->endedAt, 'datetime');
            },
            'isActive' => 'isActive'
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFilterFields()
    {
        return [
            'name', 'description', 'startedAt', 'endedAt', 'isActive'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'startedAt'], 'required'],
            ['name', 'string', 'max' => '1024'],
            ['desription', 'string'],
            [['startedAt', 'endedAt'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'      => Yii::t('api', 'Name'),
            'description'   => Yii::t('api', 'Description'),
            'startedAt'  => Yii::t('api', 'Started At'),
            'endedAt' => Yii::t('api', 'Ended At'),
            'isActive'   => Yii::t('api', 'Is Active'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            /**
             * ProjectUser has relation which need to be removed
             */
            foreach ($this->projectUsers as $item) {
                $item->delete();
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            static::SCENARIO_DEFAULT => static::OP_DELETE
        ];
    }

}
