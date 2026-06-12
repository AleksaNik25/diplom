<?php

namespace app\modules\account\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;
use app\models\Status;
use Yii;
use yii\db\Expression;

/**
 * AccountOrderSearch represents the model behind the search form of `app\models\Order`.
 */
class AccountOrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'amount', 'status_id'], 'integer'],
            [['created_at'], 'safe'],
            [['sum'], 'number'],
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
        $query = Order::find()->where(['user_id' => Yii::$app->user->id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'amount' => $this->amount,
            'sum' => $this->sum,
            'status_id' => $this->status_id,
        ]);

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

        return $dataProvider;
    }
}
