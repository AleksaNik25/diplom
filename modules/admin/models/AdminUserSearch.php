<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

class AdminUserSearch extends User
{
    public $inn;
    public $snils;
    public $shop_title;

    public function rules()
    {
        return [
            [['id', 'role'], 'integer'],
            [['login', 'email', 'phone', 'inn', 'snils', 'shop_title'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'login' => 'Логин',
            'email' => 'Email',
            'phone' => 'Телефон',
            'inn' => 'ИНН',
            'snils' => 'СНИЛС',
            'shop_title' => 'Название магазина',
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find()
            ->innerJoin('user_LE', 'user.id = user_LE.user_id')
            ->with('userLE');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'user.login', $this->login])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'user.phone', $this->phone])
            ->andFilterWhere(['like', 'user_LE.inn', $this->inn])
            ->andFilterWhere(['like', 'user_LE.snils', $this->snils])
            ->andFilterWhere(['like', 'user_LE.shop_title', $this->shop_title]);

        return $dataProvider;
    }
}
