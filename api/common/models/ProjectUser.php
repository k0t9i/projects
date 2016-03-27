<?php

namespace api\common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%project_user}}".
 *
 * @property integer $id
 * @property integer $idUser
 * @property integer $idProject
 * @property integer $attachedAt
 * @property boolean $isActive
 */
class ProjectUser extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_user}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idUser', 'idProject'], 'required'],
            ['idUser', 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id'],
            ['idProject', 'exist', 'targetClass' => Project::className(), 'targetAttribute' => 'id'],
            [['idUser', 'idProject'], 'unique', 'targetAttribute' => ['idUser', 'idProject']]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->attachedAt = time();
            $this->isActive = true;
        }
        return parent::beforeSave($insert);
    }
    
    /**
     * Find ProjectUser by idUser and idProject
     * 
     * @param integer $idUser
     * @param integer $idProject
     * @return ProjectUser|null
     */
    public static function findByUserAndProject($idUser, $idProject)
    {
        return static::find()
                ->where(['idUser' => (int)$idUser])
                ->andWhere(['idProject' => (int)$idProject])
                ->one();
    }

}
