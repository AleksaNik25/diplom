<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "basket".
 *
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property float $sum
 *
 * @property BasketItem[] $basketItems
 * @property User $user
 */
class Basket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'basket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'amount'], 'integer'],
            [['sum'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'amount' => 'Amount',
            'sum' => 'Sum',
        ];
    }

    /**
     * Gets query for [[BasketItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBasketItems()
    {
        return $this->hasMany(BasketItem::class, ['basket_id' => 'id']);
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

    public static function create()
    {
        $model = new static();
        $model->user_id = Yii::$app->user->id;
        $model->save();

        return $model;
    }

    public function addItem($product_id)
    {
        $item = BasketItem::findOne(['basket_id' => $this->id, 'product_id' => $product_id]);

        if (!$item) {
            $product = \app\models\Product::findOne($product_id);

            if (!$product) {
                throw new \yii\web\NotFoundHttpException('Товар не найден.');
            }

            $item = new BasketItem();
            $item->basket_id = $this->id;
            $item->product_id = $product_id;
            $item->price = $product->price; 
        }

        $item->amount++;
        $item->sum += $item->price;
        $item->save();

        $this->amount++;
        $this->sum += $item->price;
        $this->save();
    }

    public function addDec($item_id)
    {
        $item = BasketItem::findOne(['id' => $item_id]);
        $item->amount--;
        $item->sum -= $item->price;
        $item->save();

        $this->amount--;
        $this->sum -= $item->price;
        $this->save();

        if ($item->amount === 0) {
            $item->delete();
        }
    }

    public static function getCount()
    {
        return static::findOne(['user_id' => Yii::$app->user->id])?->amount ?? 0;
    }
}
