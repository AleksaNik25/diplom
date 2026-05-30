<?php

namespace app\models;

use Yii;

class Product extends \yii\db\ActiveRecord
{
    public $imageFiles;
    public $user_stars;

    public static function tableName()
    {
        return 'product';
    }

    public function rules()
    {
        return [
            [['estimation'], 'default', 'value' => 0.0],
            [['user_id', 'status_id', 'title', 'preview', 'care_recommendations', 'price'], 'required'],
            [['user_id', 'status_id'], 'integer'],
            [['preview', 'care_recommendations'], 'string'],
            [['estimation'], 'number'],
            [['title', 'price'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => Status::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '№ товара',
            'title' => 'Название',
            'preview' => 'Описание',
            'price' => 'Цена',
            'user_id' => 'ID продавца',
            'status_id' => 'Статус',
            'care_recommendations' => 'Рекомендации по уходу',
            'imageFiles' => 'Изображение товара',
        ];
    }


    public function getBasketItems()
    {
        return $this->hasMany(BasketItem::class, ['product_id' => 'id']);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['product_id' => 'id']);
    }

    public function getFavorits()
    {
        return $this->hasMany(Favorits::class, ['product_id' => 'id']);
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['product_id' => 'id']);
    }

    public function getProductImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }

    public function getRatings()
    {
        return $this->hasMany(Rating::class, ['product_id' => 'id']);
    }

    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getProductCategories()
    {
        return $this->hasMany(ProductCategory::class, ['product_id' => 'id']);
    }


    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('product_category', ['product_id' => 'id']);
    }


    public function getCategoryTitle(): string
    {
        $cats = $this->categories;
        if (empty($cats)) return '—';
        // return implode('<br>', array_map(fn($c) => $c->title, $cats));
        return implode(', ', array_map(fn($c) => $c->title, $cats));
    }

    public function upload()
    {
        // Если файлы не переданы — ничего не делаем, старые фото остаются
        if (empty($this->imageFiles)) {
            return true;
        }

        // Удаляем старые фото только если загружены новые
        foreach ($this->productImages as $oldImage) {
            $filePath = Yii::getAlias('@app/web/img/') . $oldImage->photo;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $oldImage->delete();
        }

        foreach ($this->imageFiles as $file) {
            $fileName = time() . '_' . Yii::$app->security->generateRandomString() . '.' . $file->extension;
            $file->saveAs('@app/web/img/' . $fileName);
            $img              = new ProductImage();
            $img->product_id  = $this->id;
            $img->photo       = $fileName;
            $img->save();
        }
        return true;
    }

    public function saveCategories(array $categoryIds): void
    {
        ProductCategory::deleteAll(['product_id' => $this->id]);
        foreach (array_unique(array_filter($categoryIds)) as $catId) {
            $pc              = new ProductCategory();
            $pc->product_id  = $this->id;
            $pc->category_id = (int)$catId;
            $pc->save();
        }
    }

    public function canLeaveComment(int $userId): bool
    {
        $hasDeliveredOrder = Order::find()
            ->innerJoin('order_item', 'order_item.order_id = order.id')
            ->innerJoin('status', 'status.id = order.status_id')
            ->where(['order.user_id' => $userId, 'order_item.product_id' => $this->id, 'status.alias' => 'finished'])
            ->exists();

        if (!$hasDeliveredOrder) return false;

        return !Comment::find()->where(['product_id' => $this->id, 'user_id' => $userId])->exists();
    }

    public function canLeaveRating(int $userId): bool
    {
        $hasDeliveredOrder = Order::find()
            ->innerJoin('order_item', 'order_item.order_id = order.id')
            ->innerJoin('status', 'status.id = order.status_id')
            ->where(['order.user_id' => $userId, 'order_item.product_id' => $this->id, 'status.alias' => 'finished'])
            ->exists();

        if (!$hasDeliveredOrder) return false;

        return !Rating::find()->where(['product_id' => $this->id, 'user_id' => $userId])->exists();
    }

    public function getAverageRating()
    {
        return self::getRatingProduct($this->id);
    }

    public static function getRatingProduct($id)
    {
        return Rating::find()->where(['product_id' => $id])->average('estimation') ?: 0;
    }

    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = $scenarios[self::SCENARIO_UPDATE] = $scenarios[self::SCENARIO_DEFAULT];
        return $scenarios;
    }
}
