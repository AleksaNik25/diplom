<?php

namespace app\models;

use Exception;
use Yii;

class Order extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'order';
    }

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
            ['date', 'validateDate'],
            ['time', 'validateTime'],
        ];
    }

    public function validateDate($attribute)
    {
        $minDate = date('Y-m-d', strtotime('+1 day'));
        if ($this->$attribute && $this->$attribute < $minDate) {
            $this->addError($attribute, 'Дата доставки не может быть раньше завтрашнего дня.');
        }
    }

    public function validateTime($attribute)
    {
        if (!empty($this->$attribute)) {
            $timeString = substr($this->$attribute, 0, 5);

            if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d)$/', $timeString)) {
                $this->addError($attribute, 'Неверный формат времени. Используйте формат ЧЧ:ММ (например, 10:00).');
                return;
            }

            if ($timeString < '10:00' || $timeString > '20:30') {
                $this->addError($attribute, 'Время доставки должно быть в диапазоне с 10:00 до 20:30.');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'id'          => '№ заказа',
            'user_id'     => 'ID покупателя',
            'created_at'  => 'Дата и время создания',
            'amount'      => 'Количество товаров',
            'sum'         => 'Итоговая сумма заказа',
            'status_id'   => 'Статус',
            'pay_type_id' => 'Способ оплаты',
            'address'     => 'Адрес доставки',
            'phone'       => 'Телефон получателя',
            'date'        => 'Желаемая дата доставки',
            'time'        => 'Желаемое время доставки',
        ];
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getPayType()
    {
        return $this->hasOne(PayType::class, ['id' => 'pay_type_id']);
    }

    public static function createOrder(int $basket_id, int $pay_type_id, string $address, string $phone, string $date, string $time): bool|int
    {
        $basket = Basket::findOne($basket_id);
        try {
            $order = new static();
            $order->user_id     = Yii::$app->user->id;
            $order->amount      = $basket->amount;
            $order->sum         = $basket->sum;
            $order->status_id   = Status::getStatusId('new');
            $order->pay_type_id = $pay_type_id;
            $order->address     = $address;
            $order->phone       = $phone;
            $order->date        = $date;
            $order->time        = $time;

            // save(false) — пропускаем валидацию, она уже прошла в контроллере
            if ($order->save(false)) {
                if ($basketItems = BasketItem::find()->where(['basket_id' => $basket_id])->all()) {
                    foreach ($basketItems as $item) {
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->load($item->attributes, '');
                        $orderItem->save(false);
                    }
                    $basket->delete();
                    return $order->id;
                }
            }
        } catch (Exception $e) {
            Yii::error($e->getMessage());
        }
        return false;
    }
}
