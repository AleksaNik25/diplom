<?php

namespace app\modules\seller\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Company;
use app\models\CompanyInfo;
use app\models\UserLE;
use Yii;
use yii\helpers\VarDumper;

/**
 * SellerCompanySearch represents the model behind the search form of `app\models\Company`.
 */
class SellerCompanySearch extends Company
{
    /**
     * {@inheritdoc}
     */
    public $title;
    public $person;
    public $inn;
    public $address;
    public $email;

    public function rules()
    {
        return [
            [['id', 'user_LE_id', 'approval'], 'integer'],
            [['title', 'person', 'inn', 'address', 'email'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            ...parent::attributeLabels(),
            'company_id' => 'Company ID',
            'title' => 'Наименование организации',
            'person' => 'Контактное лицо',
            'inn' => 'ИНН',
            'address' => 'Юридический адрес',
            'email' => 'Адрес электронной почты',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Company::find()
            ->where(['company.user_LE_id' => UserLE::geIdtUserLE()])
            ->joinWith('companyInfo');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // фильтры по таблице company
        $query->andFilterWhere([
            'company.id' => $this->id,
            'company.user_LE_id' => $this->user_LE_id,
            'company.approval' => $this->approval,
        ]);

        // фильтры по таблице company_info
        $query->andFilterWhere(['like', 'company_info.title', $this->title])
            ->andFilterWhere(['like', 'company_info.person', $this->person])
            ->andFilterWhere(['like', 'company_info.inn', $this->inn])
            ->andFilterWhere(['like', 'company_info.address', $this->address])
            ->andFilterWhere(['like', 'company_info.email', $this->email]);

        return $dataProvider;
    }
}
