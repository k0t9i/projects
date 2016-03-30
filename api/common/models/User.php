<?php

namespace api\common\models;

use Yii;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use api\common\models\queries\UserQuery;
use api\components\Filterable;
use api\rbac\HasOwnerInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $login
 * @property string $passwordHash
 * @property string $lastname
 * @property string $firstname
 * @property string $middlename
 * @property integer $idGender
 * @property string $email
 * @property boolean $isActive
 *
 * @property-read DGender|null $gender Gender relation
 * @property-read UserGroup[] $userGroups UserGroup relation
 * @property-read AccessToken[] $accessTokens AccessToken relation
 * @property-read AccessToken|null $currentAccessToken Token under which user is logged
 * @property array|null $groups Id groups from create action
 * @property-read ProjectUser[] $projectUsers ProjectUser relation
 * @property-read Project[] $projects Project relation
 */
class User extends ApiModel implements IdentityInterface, Filterable, HasOwnerInterface
{

    const SCENARIO_CREATE = 'scenario-create';
    const JUNCTION_USER_GROUP = '{{%j_user_user_group}}';

    /**
     * Password from create/update action
     *
     * @var string
     */
    public $password;

    /**
     * Password repeat from create/update action
     *
     * @var string
     */
    public $passwordRepeat;

    /**
     * @see getCurrentAccessToken
     *
     * @var AccessToken|string|null
     */
    private $_accessToken;

    /**
     * @var array|null
     */
    private $_groups;

    /**
     * Array of old group ids
     *
     * @var array
     */
    private $_oldGroups = [];

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
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'email', 'groups'], 'required'],
            ['login', 'string', 'max' => 256],
            ['login', 'unique'],
            [['lastname', 'firstname', 'middlename'], 'string', 'max' => 1024],
            ['idGender', 'exist', 'targetClass' => DGender::className(), 'targetAttribute' => 'id'],
            ['email', 'unique'],
            ['email', 'email'],
            ['email', 'string', 'max' => 1024],
            ['password', 'required', 'on' => static::SCENARIO_CREATE],
            ['passwordRepeat', 'safe'],
            ['password', 'compare', 'compareAttribute' => 'passwordRepeat'],
            ['groups', 'exist', 'targetClass' => UserGroup::className(), 'targetAttribute' => 'id', 'allowArray' => true]
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->isActive = (boolean)$this->isActive;
        }
        if ($this->password) {
            $this->passwordHash = \Yii::$app->security->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        /**
         * Update user groups from create/update actions
         */
        $insertIds = array_diff($this->groups, $this->_oldGroups);
        if ($insertIds) {
            $rows = [];
            foreach ($insertIds as $id) {
                $rows[] = [$this->id, $id];
            }
            static::getDb()
                ->createCommand()
                ->batchInsert(static::JUNCTION_USER_GROUP, ['idUser', 'idUserGroup'], $rows)
                ->execute();
        }
        $deleteIds = array_diff($this->_oldGroups, $this->groups);
        if ($deleteIds) {
            static::getDb()
                ->createCommand()
                ->delete(static::JUNCTION_USER_GROUP, [
                    'idUserGroup' => $deleteIds,
                    'idUser'      => $this->id
                ])
                ->execute();
        }

        if ($insertIds || $deleteIds) {
            /**
             * @todo Remove roles only for deleted groups
             */
            Yii::$app->authManager->revokeAll($this->id);
            /* @var $userGroup UserGroup */
            foreach ($this->userGroups as $userGroup) {
                Yii::$app->authManager->assign($userGroup->getMainRole(), $this->id);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_oldGroups = $this->groups;
    }

    /**
     * Gender relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGender()
    {
        return $this->hasOne(DGender::className(), ['id' => 'idGender']);
    }

    /**
     * AccessToken relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(AccessToken::className(), ['idUser' => 'id']);
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
        /**
         * Find only not expired token
         */
        $ret = static::find()
            ->joinWith([
                'accessTokens' => function ($query) {
                    $query->from(AccessToken::tableName() . ' at');
                }
            ])
            ->where(['at.token' => $token])
            ->andWhere('at."expiresIn" > :now', [':now' => time()])
            ->active()
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
     * Return null if user not found or password is invalid
     *
     * @param string $login
     * @param string $password
     * @return User|null
     */
    public static function findByLoginAndPassword($login, $password)
    {
        $model = static::find()
            ->where([
                'login' => $login
            ])->active()->one();

        $ret = null;
        if ($model) {
            try {
                if (Yii::$app->security->validatePassword($password, $model->passwordHash)) {
                    $ret = $model;
                }
            } catch (\yii\base\InvalidParamException $ex) {
                // if $model->passwordHash is not crypted
            }
        }

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'id'         => 'id',
            'login'      => 'login',
            'lastname'   => 'lastname',
            'firstname'  => 'firstname',
            'middlename' => 'middlename',
            'gender'     => function ($model) {
                return $model->gender ? $model->gender->localizedName : null;
            },
            'email'      => 'email',
            'lastLogin'  => function ($model) {
                $timestamp = null;
                if ($model->currentAccessToken) {
                    $timestamp = $model->currentAccessToken->createdAt;
                }
                return Yii::$app->formatter->format($timestamp, 'datetime');
            },
            'isActive'   => 'isActive'
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        $fields = parent::extraFields();
        $fields['userGroups'] = 'userGroups';
        return $fields;
    }

    /**
     * UserGroup relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroups()
    {
        return $this->hasMany(UserGroup::className(), ['id' => 'idUserGroup'])->viaTable(static::JUNCTION_USER_GROUP, ['idUser' => 'id']);
    }

    /**
     * Get AccessToken model by token from db or find last AccessToken
     * Return null if AccessToken not found
     *
     * @return AccessToken|null
     */
    public function getCurrentAccessToken()
    {
        if (!($this->_accessToken instanceof AccessToken)) {
            $this->_accessToken = AccessToken::findOne([
                'token' => $this->_accessToken
            ]);
            if (!$this->_accessToken) {
                $this->_accessToken = $this->getAccessTokens()
                    ->orderBy(AccessToken::tableName() . '."createdAt" DESC')
                    ->one();
            }
        }

        return $this->_accessToken;
    }

    /**
     * Set user groups id for saving
     *
     * @param array|integer $value
     */
    public function setGroups($value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $this->_groups = $value;
    }

    /**
     * Get setted User::$_groups
     * Get it from UserGroup relation If not set
     *
     * @return array
     */
    public function getGroups()
    {
        if (is_null($this->_groups)) {
            $this->_groups = ArrayHelper::getColumn($this->getUserGroups()->asArray()->all(), 'id');
        }
        return $this->_groups;
    }

    /**
     * ProjectUser relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUser::className(), ['idUser' => 'id']);
    }

    /**
     * Project relation
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Project::className(), ['id' => 'idProject'])
            ->via('projectUsers');
    }

    /**
     * Get AuthItem active query from permissions of this user
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        $names = array_keys(Yii::$app->authManager->getPermissionsByUser($this->id));

        return AuthItem::find()->permissions()->andWhere([
            'name' => $names
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getFilterFields()
    {
        return [
            'login', 'lastname', 'firstname', 'middlename', 'email', 'idGender',
            'isActive'
        ];
    }

    /**
     * @inheritdoc
     */
    public function isOwner($userId)
    {
        return $this->id == $userId;
    }

    public function attributeLabels()
    {
        return [
            'login'          => Yii::t('api', 'Login'),
            'lastname'       => Yii::t('api', 'Last Name'),
            'firstname'      => Yii::t('api', 'First Name'),
            'middlename'     => Yii::t('api', 'Middle Name'),
            'idGender'       => Yii::t('api', 'Gender'),
            'email'          => Yii::t('api', 'Email'),
            'isActive'       => Yii::t('api', 'Is Active'),
            'groups'         => Yii::t('api', 'Groups'),
            'password'       => Yii::t('api', 'Password'),
            'passwordRepeat' => Yii::t('api', 'Repeat Password'),
        ];
    }

}
