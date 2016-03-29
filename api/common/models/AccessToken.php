<?php

namespace api\common\models;

use Yii;
use api\common\models\User;
use api\rbac\HasOwnerInterface;
use api\components\Filterable;

/**
 * This is the model class for table "{{%access_token}}".
 *
 * @property integer $id
 * @property string $token
 * @property integer $idUser
 * @property integer $expiresIn
 * @property integer $createdAt
 *
 * @property User|null $user User relation
 */
class AccessToken extends ApiModel implements HasOwnerInterface, Filterable
{

    /**
     * Lifetime of token in seconds
     */
    const LIFETIME = 24 * 3600; // 24 hours
    const SCENARIO_CREATE = 'scenario-create';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['idUser', 'exist', 'targetClass' => User::className(), 'targetAttribute' => 'id', 'on' => static::SCENARIO_CREATE]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateToken();
            $this->expiresIn = time() + static::LIFETIME;
            $this->createdAt = time();
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
     * User relation
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'idUser']);
    }

    /**
     * Generate unique token
     */
    private function generateToken()
    {
        $this->token = hash('sha256', uniqid(microtime(true), true));
    }

    /**
     * @inheritdoc
     */
    public function isOwner($userId)
    {
        return $this->idUser == $userId;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $ret = [
            'id'        => 'id',
            'idUser'    => 'idUser',
            'expiresIn' => function($model) {
                return Yii::$app->formatter->format($model->expiresIn, 'datetime');
            },
            'createdAt' => function($model) {
                return Yii::$app->formatter->format($model->createdAt, 'datetime');
            },
        ];

        // Show token only if current user is owner of this access token or token newly created
        if ($this->idUser == \Yii::$app->user->getId() || $this->scenario == static::SCENARIO_CREATE) {
            $ret['token'] = 'token';
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function getFilterFields()
    {
        return ['idUser', 'expiresIn', 'createdAt'];
    }

}
