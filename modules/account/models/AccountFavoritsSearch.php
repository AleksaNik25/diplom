<?php

namespace app\modules\account\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Favorits;
use Yii;

class AccountFavoritsSearch extends Favorits
{
    public function rules()
    {
        return [
            [['id', 'user_id', 'product_id'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Favorits::find()->distinct();

        $query->joinWith(['product' => function ($q) {
            $q->joinWith(['categories']);
        }], false);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 12],
            'sort' => [
                'attributes' => [
                    'product_title' => [
                        'asc' => ['product.title' => SORT_ASC],
                        'desc' => ['product.title' => SORT_DESC],
                        'label' => 'Наименование товара',
                    ],
                    'category_title' => [
                        'asc' => ['category.title' => SORT_ASC],
                        'desc' => ['category.title' => SORT_DESC],
                        'label' => 'Категория товара',
                    ],
                    'id' => [
                        'asc' => ['favorits.id' => SORT_ASC],
                        'desc' => ['favorits.id' => SORT_DESC],
                        'label' => 'ID',
                    ],
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'favorits.id' => $this->id,
            'favorits.user_id' => Yii::$app->user->id,
            'favorits.product_id' => $this->product_id,
        ]);

        return $dataProvider;
    }
}
