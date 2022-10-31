<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    // public $username;
    // public $email;
    // public $password;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username'], 'trim'],
            [['username'], 'unique', 'targetClass' => 'app\models\User', 'message' => 'El nombre del usuario ingresado ya existe'],
            [['username'], 'string', 'min' => 3, 'max' => 50],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 50],
            [['email'], 'unique', 'targetClass' => 'app\models\User', 'message' => 'El correo electrónico ingresado ya existe'],
            [['password'], 'string', 'min' => 8, 'max' => 10],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Usuario',
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            dd('entra');
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save();
    }

    public static function tableName()
    {
        return '{{%user}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    // public function rules()
    // {
    //     return [
    //         ['status', 'default', 'value' => self::STATUS_INACTIVE],
    //         ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
    //         [['username', 'email', 'password'], 'required'],
    //         [['username'], 'trim'],
    //         [['username'], 'unique', 'targetClass' => 'app\models\User', 'message' => 'El nombre del usuario ingresado ya existe'],
    //         [['username'], 'string', 'min' => 3, 'max' => 50],
    //         [['email'], 'email'],
    //         [['email'], 'string', 'max' => 50],
    //         [['email'], 'unique', 'targetClass' => 'app\models\User', 'message' => 'El correo electrónico ingresado ya existe'],
    //         [['password'], 'string', 'min' => 8, 'max' => 10],
    //     ];
    // }


    // public function attributeLabels()
    // {
    //     return [
    //         'id' => 'ID',
    //         'username' => 'Usuario',
    //         'email' => 'Correo electrónico',
    //         'password' => 'Contraseña',
    //     ];
    // }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getPassword()
    {
        return '';
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
