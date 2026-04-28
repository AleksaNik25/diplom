<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;
use Yii;

/**
 * CatalogSearch represents the model behind the search form of `app\models\Product`.
 */
class CatalogSearch extends Product
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'category_id', 'status_id'], 'integer'],
            [['title', 'preview', 'care_recommendations', 'price'], 'safe'],
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
        $query = Product::find()->select("product.*, like.like_count, dislike.dislike_count")
            ->where(['>', 'amount', 0])
            ->with([
                'productImage',
                'category',
                // `favourites`,
            ])
            ->leftJoin(
                ["like" => "(SELECT COUNT(*) AS like_count, product_id
                                        FROM `user_action_product`
                                        WHERE `action` = 1
                                        GROUP BY product_id)"],
                "like.product_id = product.id"
            )
            ->leftJoin(
                ["dislike" => "(SELECT COUNT(*) AS dislike_count, product_id
                                        FROM `user_action_product`
                                        WHERE `action` = 0
                                        GROUP BY product_id)"],
                "dislike.product_id = product.id"
            );

        if (Yii::$app->user?->identity?->isClient) {

            $query
                ->with([
                    'favourites' => function ($query) {
                        $query->andWhere(['user_id' => Yii::$app->user?->id]);
                    },
                ]);
        }

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
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'preview', $this->preview])
            ->andFilterWhere(['like', 'care_recommendations', $this->care_recommendations])
            ->andFilterWhere(['like', 'price', $this->price]);

        return $dataProvider;
    }
}
