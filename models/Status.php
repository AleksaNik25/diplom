<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 *
 * @property Order[] $orders
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'alias'], 'required'],
            [['title', 'alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'alias' => 'Alias',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['status_id' => 'id']);
    }

    public static function getStatusId($alias) 
    {
        return static::findOne(["alias" => $alias])->id;
    }

    public static function getStatuses(): array
    {
        return static::find()
            ->select('title')
            ->indexBy('id')
            ->column()
        ;
    }

    public function getProducts()
    {
        return $this->hasMany(Product::class, ['status_id' => 'id']);
    }

    public static function getStatusesAlias(): array
    {
        return static::find()
            ->select('id')
            ->indexBy('alias')
            ->column();
    }
}
