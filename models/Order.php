<?php

namespace app\models;

use Exception;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property int $user_id
 * @property string $created_at
 * @property int $amount
 * @property float $sum
 * @property int $status_id
 *
 * @property OrderItem[] $orderItems
 * @property Status $status
 * @property User $user
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'created_at', 'status_id'], 'required'],
            [['user_id', 'amount', 'status_id'], 'integer'],
            [['created_at'], 'safe'],
            [['sum'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Номер заказа',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'amount' => 'Amount',
            'sum' => 'Sum',
            'status_id' => 'Статус заказа',
        ];
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function createOrder(int $basket_id): bool | int
    {
        $basket = Basket::findOne($basket_id);
        try {
            $order = new static();
            $order->user_id = Yii::$app->user->id;
            $order->amount = $basket->amount;
            $order->sum = $basket->sum;
            $order->status_id = Status::getStatusId('new');
            if ($order->save()) {
                if ($basketItems = BasketItem::find()->where(['basket_id' => $basket_id])->all()) {

                    foreach ($basketItems as $item) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->load($item->attributes, '');
                        $orderItem->save();
                    }
                    $basket->delete();
                    return $order->id;
                }
            }
        } catch (Exception $e) {
            if (isset($order) && $order->id) {
                $order->delete();
            }
            Yii::debug($e->getMessage());
        }
        return false;
    }
}
