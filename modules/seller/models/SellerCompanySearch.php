<?php

namespace app\modules\seller\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Company;
use app\models\CompanyInfo;
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
    public $inn;
    public $address;
    public $email;

    public function rules()
    {
        return [
            [['id', 'user_LE_id', 'approval'], 'integer'],
            [['title', 'inn', 'address', 'email'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            ...parent::attributeLabels(),
            'company_id' => 'Company ID',
            'title' => 'Наименование организации',
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
    public function search($params)
    {
        $query = Company::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_LE_id' => $this->user_LE_id,
            'approval' => $this->approval,
        ]);

        if ($this->title || $this->inn || $this->address || $this->email) {

            $q = CompanyInfo::find()
                ->select('company_id')
                ->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'inn', $this->inn])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'email', $this->email]);

            // VarDumper::dump($q->createCommand()->rawSql, 10, true);
            // VarDumper::dump($q->column(), 10, true);

            $query->andWhere(['id' => $q->column()]);

        }
        // Yii::debug();
        // VarDumper::dump($query->createCommand()->rawSql, 10, true);
        // die;

        return $dataProvider;
    }
}
