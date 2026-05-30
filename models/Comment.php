<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $product_id
 * @property string $text
 * @property int $user_id
 *
 * @property Product $product
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    public $user_stars;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'updated_at'], 'default', 'value' => null],
            [['parent_id', 'product_id', 'user_id'], 'integer'],
            [['product_id', 'text', 'user_id'], 'required'],
            [
                ['user_stars'],
                'required',
                'message' => 'Пожалуйста, поставьте оценку товару перед отправкой отзыва',
                'when' => fn($model) => empty($model->parent_id),
                'whenClient' => "function(attribute, value) { return !$('input[name=\"Comment[parent_id]\"]').val(); }"
            ],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_stars'], 'number', 'min' => 1, 'max' => 5],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function validateReplyPermissions($attribute, $params)
    {
        if (!$this->$attribute) return; 

        $parentComment = Comment::findOne($this->$attribute);
        if (!$parentComment) {
            $this->addError($attribute, 'Комментарий не найден.');
            return;
        }

        $product = $this->product;
        $productOwner = $product->user; // Владелец товара (Продавец)
        $currentUser = Yii::$app->user->identity;

        $rootAuthorId = $this->getRootAuthorId($parentComment);

        if ($parentComment->user->isSeller && $parentComment->user_id === $productOwner->id) {
            // Отвечать продавцу может только тот, кто оставил первоначальный отзыв
            if ($currentUser->id !== $rootAuthorId) {
                $this->addError($attribute, 'Отвечать продавцу может только автор отзыва.');
            }
        } else {
            // Отвечать покупателю может только владелец этого товара
            if (!$currentUser->isSeller || $currentUser->id !== $productOwner->id) {
                $this->addError($attribute, 'Отвечать на отзывы может только продавец товара.');
            }
        }
    }

    // Метод для поиска автора корня ветки
    private function getRootAuthorId($comment)
    {
        if ($comment->parent_id) {
            $parent = Comment::findOne($comment->parent_id);
            return $this->getRootAuthorId($parent);
        }
        return $comment->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'text' => 'Отзыв о товаре',
            'user_id' => 'User ID',
            'user_stars' => 'Ваша оценка',
        ];
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

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getParent()
    {
        return $this->hasOne(Comment::class, ['id' => 'parent_id']);
    }

    public function getReplies()
    {
        return $this->hasMany(Comment::class, ['parent_id' => 'id'])->orderBy(['created_at' => SORT_ASC]);
    }


    public function canReply()
    {
        if (Yii::$app->user->isGuest) return false;

        $currentUser = Yii::$app->user->identity;
        $productOwner = $this->product->user;

        // Если комментарий от Продавца (владельца товара) -> Ответить может автор отзыва
        if ($this->user_id === $productOwner->id) {
            $rootId = $this->getRootAuthorId($this); // Ищем автора корня
            return $currentUser->id === $rootId;
        }

        // Если комментарий от Покупателя -> Ответить может Продавец
        return $currentUser->isSeller && $currentUser->id === $productOwner->id;
    }

    public function getIsSellerComment()
    {
        return $this->user->isSeller && $this->user_id === $this->product->user_id;
    }
}
