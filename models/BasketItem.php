<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "basket_item".
 *
 * @property int $id
 * @property int $basket_id
 * @property int $product_id
 * @property int $amount
 * @property float $price
 * @property float $sum
 *
 * @property Basket $basket
 * @property Product $product
 */
class BasketItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'basket_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['basket_id', 'product_id'], 'required'],
            [['basket_id', 'product_id', 'amount'], 'integer'],
            [['price', 'sum'], 'number'],
            [['basket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Basket::class, 'targetAttribute' => ['basket_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'basket_id' => 'Basket ID',
            'product_id' => 'Product ID',
            'amount' => 'Amount',
            'price' => 'Price',
            'sum' => 'Sum',
        ];
    }

    /**
     * Gets query for [[Basket]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBasket()
    {
        return $this->hasOne(Basket::class, ['id' => 'basket_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
