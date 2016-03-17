<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $lastname
 * @property string $firstname
 * @property string $middlename
 * @property integer $id_gender
 * @property string $email
 * @property string $last_login
 *
 * @property-read DGender $gender
 * @property-read UserGroup[] $userGroups
 * @property-read AccessToken[] $accessTokens
 * @property-read AccessToken $currentAccessToken
 */
class User extends ActiveRecord implements IdentityInterface
{

    private $_accessToken;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(DGender::className(), ['id' => 'id_gender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(AccessToken::className(), ['id_user' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $ret = static::find()
                ->joinWith([
                    'accessTokens' => function($query) {
                        $query->from(AccessToken::tableName() . ' at');
                    }
                ])
                ->where(['at.token' => $token])
                ->andWhere('at.expires_in > :now', [':now' => time()])
                ->one();
        if ($ret) {
            $ret->_accessToken = $token;
        }
        
        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        throw new NotSupportedException();
    }

    /**
     * Find User model by login and validate it password
     * Return null if user not found or password invalid
     * 
     * @param string $login
     * @param string $password
     * @return User|null
     */
    public static function findByLoginAndPassword($login, $password)
    {
        $model = static::findOne([
                    'login' => $login
        ]);

        $ret = null;
        if ($model && Yii::$app->security->validatePassword($password, $model->password)) {
            $ret = $model;
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id' => 'id',
            'login' => 'login',
            'lastname' => 'lastname',
            'firstname' => 'firstname',
            'middlename' => 'middlename',
            'gender' => function($model) {
                return $model->gender ? $model->gender->name : null;
            },
            'email' => 'email',
            'lastLogin' => 'last_login',
        ];
    }
    
    public function extraFields()
    {
        return [
            'userGroups' => 'userGroups'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::className(), ['id' => 'id_user_group'])->viaTable('{{%j_user_user_group}}', ['id_user' => 'id']);
    }
    
    public function getCurrentAccessToken()
    {
        if (!($this->_accessToken instanceof AccessToken)) {
            $this->_accessToken = AccessToken::findOne([
                'token' => $this->_accessToken
            ]);
        }
        
        return $this->_accessToken;
    }

}
