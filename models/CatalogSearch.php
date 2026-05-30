<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;
use app\models\Status;
use Yii;

class CatalogSearch extends Product
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

        $query->with(['productImages', 'categories']);

        if (Yii::$app->user?->identity?->isClient) {
            $query->with([
                'favorits' => function ($q) {
                    $q->andWhere(['user_id' => Yii::$app->user->id]);
                },
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => ['pageSize' => 24],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // Всегда только товары со статусом on sale
        $query->andWhere(['product.status_id' => Status::getStatusId('on sale')]);

        // Фильтр по названию
        $query->andFilterWhere(['like', 'product.title', $this->title]);

        // Фильтр по категории/подкатегории через product_category
        if ($this->category_id) {
            $selectedCat = Category::findOne($this->category_id);

            if ($selectedCat && $selectedCat->parent_id === null) {
                // Выбрана корневая категория — ищем товары у которых есть
                // хотя бы одна подкатегория этой корневой
                $childIds = Category::find()
                    ->select('id')
                    ->where(['parent_id' => $selectedCat->id])
                    ->column();

                $query->innerJoin(
                    'product_category pc',
                    'pc.product_id = product.id'
                )->andWhere(['pc.category_id' => $childIds]);
            } else {
                // Выбрана конкретная подкатегория
                $query->innerJoin(
                    'product_category pc',
                    'pc.product_id = product.id'
                )->andWhere(['pc.category_id' => $this->category_id]);
            }
        }

        return $dataProvider;
    }
}
