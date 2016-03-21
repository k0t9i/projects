<?php

namespace api\common\models;

use Yii;

/**
 * This is the model class for table "{{%project_user}}".
 *
 * @property integer $id
 * @property integer $id_user
 * @property integer $id_project
 * @property integer $attached_at
 * @property boolean $is_active
 */
class ProjectUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project_user}}';
    }
    
}
