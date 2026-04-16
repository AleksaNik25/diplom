<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property int $user_LE_id
 * @property int $approval
 *
 * @property CompanyDoc[] $companyDocs
 * @property CompanyInfo $companyInfo
 * @property UserLE $userLE
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_LE_id'], 'required'],
            [['user_LE_id', 'approval'], 'integer'],
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
            'approval' => 'Approval',
        ];
    }

    /**
     * Gets query for [[CompanyDocs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyDocs()
    {
        return $this->hasMany(CompanyDoc::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[CompanyInfo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyInfo()
    {
        return $this->hasOne(CompanyInfo::class, ['company_id' => 'id']);
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
