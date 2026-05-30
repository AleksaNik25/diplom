<?php

namespace app\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title
 * @property int $parent_id
 *
 * @property Category[] $categories
 * @property Category $parent
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['parent_id', 'extend'], 'default', 'value' => null],
            [['parent_id', 'extend'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'title' => 'Название',
            'parent_id' => 'Категория',
            'extend' => 'Расширение',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::class, ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'id']);
    }

    public static function getCategory()
    {
        return static::find()
            ->select('title')
            ->indexBy('id')
            ->column()
        ;
    }

    public static function getCategoryes()
    {
        return (new Query())
            ->select('title')
            ->from(self::tableName())
            ->indexBy('id')
            ->column();
    }
}
