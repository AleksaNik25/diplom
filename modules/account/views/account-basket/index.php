<?php

use app\models\Basket;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;
$basketNoEmpty = $basket && $dataProviderItems->totalCount;
?>
<div class="basket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin([
        'id' => 'cart-pjax',
        'enablePushState' => false,
        'timeout' => 5000,
    ]); ?>

    <div class="d-flex justify-content-between mt-3 mb-3">
        <div>
            <?= Html::a('Продолжить покупки', ['/catalog'], ['class' => 'btn btn-outline-info', 'data-pjax' => 0]) ?>
        </div>
        <div>
            <?= $basketNoEmpty
                ? Html::a('Очистить корзину', ['clear', 'id' => $basket->id], ['class' => 'btn btn-outline-danger cart-btn'])
                : ""
            ?>
        </div>
    </div>

    <?php if ($dataProviderItems->totalCount): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProviderItems,
            'itemOptions' => ['class' => 'item'],
            'itemView' => 'item',
            'pager' => [
                'class' => LinkPager::class
            ],
        ]) ?>

    <?php else: ?>
        <div class="alert alert-info" role="alert">
            В корзине пока нет товаров
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-end mt-3">
        <?= $basketNoEmpty
            ? Html::a('Оформить заказ', ['/account/account-order/create', 'basket_id' => $basket->id], ['class' => 'btn btn-success'])
            : ""
        ?>
    </div>

    <?php Pjax::end(); ?>