<?php

namespace app\modules\admin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;
use app\models\Status;
use yii\db\Expression;

class AdminOrderSearch extends Order
{
    public function rules()
    {
        return [
            [['id', 'user_id', 'amount', 'status_id'], 'integer'],
            [['created_at'], 'safe'],
            [['sum'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Order::find()->with('user');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'created_at',
                    'sum',
                    'status_id',
                ],
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // Фильтр по дате: поддержка частичного совпадения (LIKE)
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['user_id' => $this->user_id]);
        $query->andFilterWhere(['amount' => $this->amount]);
        $query->andFilterWhere(['sum' => $this->sum]);
        $query->andFilterWhere(['status_id' => $this->status_id]);

        if ($this->created_at) {
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        }

        // Если сортировка не задана пользователем — сортируем по статусу+дате
        if (empty($params['sort'])) {
            $statusesAlias = Status::getStatusesAlias();
            $newStatusId = $statusesAlias['new'] ?? null;
            $inDeliveryStatusId = $statusesAlias['in delivery'] ?? null;

            $caseParts = [];
            if ($newStatusId !== null) {
                $caseParts[] = "WHEN status_id = $newStatusId THEN 1";
            }
            if ($inDeliveryStatusId !== null) {
                $caseParts[] = "WHEN status_id = $inDeliveryStatusId THEN 2";
            }
            $caseParts[] = "ELSE 3";

            $caseExpression = "CASE " . implode(" ", $caseParts) . " END";
            $query->orderBy([
                new Expression($caseExpression),
                'created_at' => SORT_DESC,
            ]);
        }

        return $dataProvider;
    }
}
