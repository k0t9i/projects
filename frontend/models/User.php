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
 * @property string $lastLogin
 * @property string $authKey
 *
 * @property-read DGender $gender
 */
class User extends ActiveRecord implements IdentityInterface
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password', 'email'], 'required'],
            [['id_gender'], 'integer'],
            [['lastLogin'], 'safe'],
            [['login', 'email', 'authKey'], 'string', 'max' => 256],
            [['password'], 'string', 'max' => 64],
            [['lastname', 'firstname', 'middlename'], 'string', 'max' => 1024],
            [['email'], 'unique'],
            [['login'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('frontend', 'Login'),
            'password' => Yii::t('frontend', 'Password'),
            'lastname' => Yii::t('frontend', 'Lastname'),
            'firstname' => Yii::t('frontend', 'Firstname'),
            'middlename' => Yii::t('frontend', 'Middlename'),
            'gender' => Yii::t('frontend', 'Gender'),
            'id_gender' => Yii::t('frontend', 'Gender'),
            'email' => Yii::t('frontend', 'Email'),
            'lastLogin' => Yii::t('frontend', 'Last login')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(DGender::className(), ['id' => 'id_gender']);
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
        return static::findOne([
            'accessToken' => $token
        ]);
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
        if ($model && Yii::$app->security->validatePassword($password, $model->password)){
            $ret = $model;
        }
        
        return $ret;
    }

}
