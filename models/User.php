<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property int $role
 * @property string $auth_key
 *
 * @property Product[] $products
 */
class User extends ActiveRecord implements IdentityInterface
{

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'surname', 'patronymic', 'login', 'password', 'email', 'phone', 'auth_key'], 'required'],
            [['role'], 'integer'],
            [['name', 'surname', 'patronymic', 'login', 'password', 'email', 'phone', 'auth_key'], 'string', 'max' => 255],
            [['login'], 'unique'],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'patronymic' => 'Отчество',
            'login' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Почтовый адрес',
            'phone' => 'Номер телефона',
            'role' => 'Role',
            'auth_key' => 'Auth Key',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Favorits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorits()
    {
        return $this->hasMany(Favorits::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserDocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserDocs()
    {
        return $this->hasMany(UserDoc::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Ratings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Rating::class, ['user_id' => 'id']);
    }

        /**
     * Gets query for [[UserLE]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserLE()
    {
        return $this->hasOne(UserLE::class, ['user_id' => 'id']);
    }




    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    public static function findByUsername($login)
    {
        return self::findOne(['login' => $login]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function getIsAdmin()
    {
        return $this->role === 1;
    }

    public function getIsSeller()
    {
        return (bool)$this?->userLE;
    }
    
    public function getIsClient()
    {
        return !(bool)$this?->userLE && !$this->isAdmin;
    }



}
