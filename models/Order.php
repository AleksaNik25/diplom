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
            [['user_id', 'address', 'phone', 'date', 'time', 'status_id', 'pay_type_id'], 'required'],
            [['amount'], 'default', 'value' => 0],
            [['sum'], 'default', 'value' => 0.00],
            [['user_id', 'amount', 'status_id', 'pay_type_id'], 'integer'],
            [['created_at', 'date', 'time'], 'safe'],
            [['sum'], 'number'],
            [['address', 'phone'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
            [['pay_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PayType::class, 'targetAttribute' => ['pay_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№ заказа',
            'user_id' => 'ID покупателя',
            'created_at' => 'Дата и время создания',
            'amount' => 'Количество товаров',
            'sum' => 'Итоговая сумма заказа',
            'status_id' => 'Статус',
            'pay_type_id' => 'Способ оплаты',
            'address' => 'Адрес доставки',
            'phone' => 'Телефон получателя',
            'date' => 'Желаемая дата доставки',
            'time' => 'Желаемое время доставки',
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

    public static function createOrder(int $basket_id, int $pay_type_id, string $address, string $phone, string $date, string $time): bool|int
    {
        $basket = Basket::findOne($basket_id);
        try {
            $order = new static();
            $order->user_id = Yii::$app->user->id;
            $order->amount = $basket->amount;
            $order->sum = $basket->sum;
            $order->status_id = Status::getStatusId('new');
            $order->pay_type_id = $pay_type_id;
            $order->address = $address;
            $order->phone = $phone;
            $order->date = $date;
            $order->time = $time;

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
            } else {
                var_dump($order->errors);
                die;
            }
        } catch (Exception $e) {
            var_dump($e->getMessage());
            die;
            if (isset($order) && $order->id) {
                $order->delete();
            }
            Yii::debug($e->getMessage());
        }
        return false;
    }

    public function getPayType()
    {
        return $this->hasOne(PayType::class, ['id' => 'pay_type_id']);
    }
}
