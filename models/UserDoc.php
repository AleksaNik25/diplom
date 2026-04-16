<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_doc".
 *
 * @property int $id
 * @property int $user_LE_id
 * @property string $photo
 *
 * @property UserLE $userLE
 */
class UserDoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_LE_id', 'photo'], 'required'],
            [['user_LE_id'], 'integer'],
            [['photo'], 'string', 'max' => 255],
            [['user_LE_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserLE::class, 'targetAttribute' => ['user_LE_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_LE_id' => 'User Le ID',
            'photo' => 'Photo',
        ];
    }

    /**
     * Gets query for [[UserLE]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserLE()
    {
        return $this->hasOne(UserLE::class, ['id' => 'user_LE_id']);
    }
}
