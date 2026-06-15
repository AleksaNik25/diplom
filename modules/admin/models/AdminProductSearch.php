<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

class AdminProductSearch extends Product
{
    public $category_id;

    public function rules()
    {
        return [
            [['id', 'user_id', 'status_id'], 'integer'],
            [['category_id'], 'integer'],
            [['title', 'preview', 'care_recommendations', 'price'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find()->alias('product')->distinct();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'product.id' => $this->id,
            'product.user_id' => $this->user_id,
            'product.status_id'=> $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'product.title', $this->title])
            ->andFilterWhere(['like', 'product.preview', $this->preview])
            ->andFilterWhere(['like', 'product.care_recommendations', $this->care_recommendations])
            ->andFilterWhere(['like', 'product.price', $this->price]);

        if ($this->category_id) {
            $query->innerJoin(
                'product_category pc',
                'pc.product_id = product.id'
            )->andWhere(['pc.category_id' => $this->category_id]);
        }

        return $dataProvider;
    }
}
