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

}
