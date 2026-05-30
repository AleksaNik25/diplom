<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * AdminProductSearch represents the model behind the search form of `app\models\Product`.
 */
class AdminProductSearch extends Product
{
    public $category_id; // Виртуальное поле для фильтрации

    public function rules()
    {
        return [
            [['id', 'user_id', 'status_id'], 'integer'],
            [['category_id'], 'integer'], // Добавлено
            [['title', 'preview', 'care_recommendations', 'price'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        // distinct() обязателен при фильтрации через Many-to-Many, чтобы не дублировать товары
        $query = Product::find()->distinct();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // Подключаем связь categories для фильтрации, но без жадной загрузки (false)
        $query->joinWith(['categories' => function ($q) {
            $q->onCondition(['category.id' => $this->category_id]);
        }], false);

        $query->andFilterWhere([
            'product.id' => $this->id,
            'product.user_id' => $this->user_id,
            'product.status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'product.title', $this->title])
            ->andFilterWhere(['like', 'product.preview', $this->preview])
            ->andFilterWhere(['like', 'product.care_recommendations', $this->care_recommendations])
            ->andFilterWhere(['like', 'product.price', $this->price]);

        return $dataProvider;
    }
}
