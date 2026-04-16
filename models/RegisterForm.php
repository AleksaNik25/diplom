<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterForm extends Model
{
    public $surname;
    public $name;
    public $patronymic;
    public $email;
    public $phone;
    public $login;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'patronymic', 'login', 'password', 'email', 'phone'], 'required'],
            [['surname', 'name', 'patronymic', 'login', 'password', 'email', 'phone'], 'string', 'max' => 255],
            [['login'], 'unique', 'targetClass' => User::class],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'login' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Почтовый адрес',
            'phone' => 'Номер телефона',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->load($this->attributes, '');
            $user->password = Yii::$app->security->generatePasswordHash($user->password);
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->save();
        }
        return $user ?? false;
    }
}
