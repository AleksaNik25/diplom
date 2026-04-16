<?php

namespace app\models;

use Symfony\Component\VarDumper\VarDumper;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterLEForm extends Model
{
    public $surname;
    public $name;
    public $patronymic;
    public $email;
    public $phone;
    public $login;
    public $password;

    public $inn;
    public $snils;
    public $shop_title;

    public $imageFiles;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['surname', 'name', 'patronymic', 'login', 'password', 'email', 'phone'], 'required'],
            [['surname', 'name', 'patronymic', 'login', 'password', 'email', 'phone'], 'string', 'max' => 255],
            [['login'], 'unique', 'targetClass' => User::class],
            [['imageFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, avif, jpeg', 'maxFiles' => 4],
            [['inn', 'snils', 'shop_title'], 'required'],
            [['inn', 'snils', 'shop_title'], 'string', 'max' => 255],
            // [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'inn' => 'ИНН',
            'snils' => 'СНИЛС',
            'shop_title' => 'Название магазина',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function registerLE()
    {
        if ($this->validate()) {
            $user = new User();
            $user->load($this->attributes, '');
            $user->password = Yii::$app->security->generatePasswordHash($user->password);
            $user->auth_key = Yii::$app->security->generateRandomString();
            if ($user->save()) {
                $user_LE = new UserLE();
                $user_LE->load($this->attributes, '');
                $user_LE->user_id = $user->id;
                $user_LE->save();
            }
        }
        return $user ?? false;
    }


    public function upload($user)
    {
        if ($this->imageFiles) {
            foreach ($this->imageFiles as $file) {
                $fileName = time() . '_' . Yii::$app->security->generateRandomString() . '.' . $file->extension;
                $file->saveAs('@app/web/doc/' . $fileName);
                $userDoc = new UserDoc();
                $userDoc->user_LE_id = UserLE::geIdtUserLE($user->id);
                $userDoc->photo = $fileName;
                if (!$userDoc->save()) {
                    VarDumper::dump($userDoc->errors, 10, true);
            die;
                }
            }
            return true;
        } else {
            VarDumper::dump($this->errors, 10, true);
            die;
        }
        return false;
    }
}
