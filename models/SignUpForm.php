<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\web\NotFoundHttpException;

class SignUpForm extends Model
{
    public $username;
    public $email;
    public $password;

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
}
