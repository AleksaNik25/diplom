<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_LE".
 *
 * @property int $id
 * @property int $user_id
 * @property string $inn
 * @property string $snils
 * @property string $shop_title
 * @property int $approval
 *
 * @property Company[] $companies
 * @property User $user
 * @property UserDoc[] $userDocs
 */
class UserLE extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_LE';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'inn', 'snils', 'shop_title'], 'required'],
            [['user_id', 'approval'], 'integer'],
            [['inn', 'snils', 'shop_title'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'inn' => 'Inn',
            'snils' => 'Snils',
            'shop_title' => 'Shop Title',
            'approval' => 'Approval',
        ];
    }

    /**
     * Gets query for [[Companies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Company::class, ['user_LE_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[UserDocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserDocs()
    {
        return $this->hasMany(UserDoc::class, ['user_LE_id' => 'id']);
    }

    public static function geIdtUserLE($user_id = null)
    {
        $user_id = $user_id ?? Yii::$app->user->id;
        return (self::findOne(["user_id" => $user_id]))->id;
    }
}
