<?php

namespace frontend\models;

use Yii;
use frontend\models\User;

/**
 * This is the model class for table "{{%access_token}}".
 *
 * @property integer $id
 * @property string $token
 * @property integer $id_user
 * @property integer $expires_in
 *
 * @property User $user
 */
class AccessToken extends \yii\db\ActiveRecord
{
    const LIFETIME = 24 * 3600;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id_user', 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id']
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateToken();
            $this->expires_in = time() + static::LIFETIME;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%access_token}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
    
    private function generateToken()
    {
        $this->token = hash('sha256', uniqid(microtime(true), true));
    }

}
