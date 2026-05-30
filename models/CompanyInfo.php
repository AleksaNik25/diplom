<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_info".
 *
 * @property int $company_id
 * @property string $title
 * @property string $inn
 * @property string $address
 * @property string $email
 *
 * @property Company $company
 */
class CompanyInfo extends \yii\db\ActiveRecord
{
    const SCENARIO_BEFORE_CREATE = "before_create";
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'title', 'inn', 'address', 'email', 'person'], 'required'],
            [['company_id'], 'integer'],
            [['title', 'inn', 'address', 'email', 'person'], 'string', 'max' => 255],
            [['company_id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'company_id' => 'Company ID',
            'title' => 'Наименование организации',
            'inn' => 'ИНН',
            'address' => 'Юридический адрес',
            'email' => 'Адрес электронной почты',
            'person' => 'Контактное лицо',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::class, ['id' => 'company_id']);
    }
}
