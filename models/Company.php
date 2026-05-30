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
    public $docFiles;

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
            [['docFiles'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf', 'maxFiles' => 10],
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

    /**
     * Проверяет, подтверждена ли компания продавца (текущего пользователя)
     */
    public static function isCurrentSellerApproved(): bool
    {
        $userLeId = UserLE::geIdtUserLE();
        if (!$userLeId) {
            return false;
        }
        return self::find()
            ->where(['user_LE_id' => $userLeId, 'approval' => 1])
            ->exists();
    }

    public function uploadDocs()
    {
        if (empty($this->docFiles)) {
            return true;
        }

        foreach ($this->docFiles as $file) {
            $fileName = time() . '_' . Yii::$app->security->generateRandomString() . '.' . $file->extension;
            $file->saveAs('@app/web/company_docs/' . $fileName);
            $doc = new CompanyDoc();
            $doc->company_id = $this->id;
            $doc->photo = $fileName;
            if (!$doc->save()) {
                return false;
            }
        }
        return true;
    }
}
