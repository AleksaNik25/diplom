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
$basketNoEmpty = $basket && $dataProviderItems->totalCount;
?>
<div class="basket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin([
        'id' => 'basket-pjax',
        'enablePushState' => false,
        'timeout' => 5000,
    ]); ?>

    <div class="d-flex justify-content-between mt-3 mb-3">
        <div>
            <?= Html::a('Продолжить покупки', ['/catalog'], ['class' => 'btn btn-outline-primary', 'data-pjax' => 0]) ?>
        </div>
        <div>
            <?= $basketNoEmpty
                ? Html::a('Очистить корзину', ['clear', 'id' => $basket->id], ['class' => 'btn btn-outline-danger', 'id'    => 'btn-clear-cart'])
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
            'summary' => '',
        ]) ?>

        <div class="border-white border-top border-2 py-3 order-total fw-bold fs-3">
            <div class="row align-items-start">
                <div class="col-1 offset-1">
                    Итого:
                </div>

                <div class="col-2 text-center">
                    <?= $basket->amount ?> шт.
                </div>

                <div class="col-2 offset-5 text-end">
                    <?= Yii::$app->formatter->asDecimal($basket->sum, 2) ?> ₽
                    <div class="text-end mt-3">
                        <?= $basketNoEmpty
                            ? Html::a('Оформить заказ', ['/account/account-order/create', 'basket_id' => $basket->id], ['class' => 'btn btn-success', 'data-pjax' => 0])
                            : ""
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-primary" role="alert">
            В корзине пока нет товаров
        </div>
    <?php endif ?>

    <?php Pjax::end(); ?>

    <?php
    $this->registerJsFile('/js/basket.js', ['depends' => 'yii\web\YiiAsset']);
    ?>

</div>