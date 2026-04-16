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
            [['title', 'inn', 'address', 'email'], 'required'],
            [['company_id'], 'integer'],
            [['title', 'inn', 'address', 'email'], 'string', 'max' => 255],

            [['company_id'], 'required', "on" => self::SCENARIO_BEFORE_CREATE],
            [['company_id'], 'unique', "on" => self::SCENARIO_BEFORE_CREATE],
            [['company_id'], 'exist', "on" => self::SCENARIO_BEFORE_CREATE, 'skipOnError' => true, 'targetClass' => Company::class, 'targetAttribute' => ['company_id' => 'id']], 
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
