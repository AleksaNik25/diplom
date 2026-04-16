<?php

namespace app\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $preview
 * @property string $care_recommendations
 * @property string $price
 *
 * @property BasketItem[] $basketItems
 * @property Category $category
 * @property Comment[] $comments
 * @property Favorits[] $favorits
 * @property LikeDislike[] $likeDislikes
 * @property OrderItem[] $orderItems
 * @property ProductImage[] $productImages
 * @property Rating[] $ratings
 */
class Product extends \yii\db\ActiveRecord
{
    public $imageFiles;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'status_id', 'title', 'preview', 'care_recommendations', 'price'], 'required'],
            [['user_id', 'category_id', 'status_id'], 'integer'],
            [['preview', 'care_recommendations'], 'string'],
            [['title', 'price'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, avif, jpeg', 'maxFiles' => 4],
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
            'preview' => 'Описание',
            'price' => 'Цена',
            'img' => 'Изображение',
            'user_id' => 'Пользователь',
            'status_id' => 'Статус',
            'category_id' => 'Категория',
            'care_recommendations' => 'Рекомендации по уходу',
            'imageFiles' => 'Изображение товара',
        ];
    }

    /**
     * Gets query for [[BasketItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBasketItems()
    {
        return $this->hasMany(BasketItem::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Favorits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorits()
    {
        return $this->hasMany(Favorits::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[LikeDislikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikeDislikes()
    {
        return $this->hasMany(LikeDislike::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Ratings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Rating::class, ['product_id' => 'id']);
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $file) {
                $fileName = time() . '_' . Yii::$app->security->generateRandomString() . '.' . $file->extension;
                $file->saveAs('@app/web/img/' . $fileName );
                $img = new ProductImage();
                $img->product_id = $this->id;
                $img->photo = $fileName;
                $img->save();
            }
            return true;
        } else {
            VarDumper::dump($this->errors, 10, true); die;
            return false;
        }
    }

    
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
}
