<?php

use kartik\rating\StarRating;
use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

$images = array_map(function ($item) use ($model) {
    return  "<img src=\"/img/$item->photo\" alt=\"$model->title\" style=\"max-width: 18rem; min-height: 286.4px; max-height: 286.4px; \" class=\"img-cart-style\">";
}, $model->productImages);

$viewUrl = Url::to(['view', 'id' => $model->id]);

$favorits_id = $model?->favorits ? $model?->favorits[0]?->id : false;
$favorits_color = $favorits_id
    ? "text-danger"
    : "text-white";
?>



<div class="card mt-4 cart-style">
    <div class="icon-place">
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity?->isClient): ?>
            <i
                class="fas fa-heart icon-favourite fs-4 <?= $favorits_color ?>"
                data-url="<?= $favorits_id
                                ? Url::to(['/account/account-favorits/remove', 'id' => $favorits_id])
                                : Url::to(['/account/account-favorits/add', 'product_id' => $model->id]) ?>">
            </i>
        <?php endif ?>
    </div>

    <a href="<?= $viewUrl ?>" class="text-decoration-none">
        <div class="img-cart-style">
            <?= Carousel::widget([
                'items' => $images,
                'options' => [
                    'class' => 'carousel slide carousel-fade',
                    // 'data-bs-ride' => "carousel",
                    'data-bs-interval' => '7000',
                    'style' => 'max-height: 400px;',
                ],
            ]);
            ?>
        </div>
    </a>


    <div class="card-body pb-0">
        <div class="text-center pb-2">
            <h5 class="card-title fw-bold">
                <a href="<?= $viewUrl ?>" class="text-decoration-none text-dark">
                    <?= Html::encode($model->title) ?>
                </a>
            </h5>
            <div>
                <?php
                $mainCat = null;
                foreach ($model->categories as $cat) {
                    if (!$cat->extend) {
                        $mainCat = $cat;
                        break;
                    }
                }
                ?>
                <span class="card-text text-secondary"><?= $mainCat ? Html::encode($mainCat->title) : '—' ?></span>
            </div>
            <div>
                <p class="card-text"><?= $model->price ?><span> ₽</span>
            </div>

        </div>

        <div class="d-flex justify-content-center">
            <?php $avgRating = $model->getAverageRating(); ?>
            <?php if ($avgRating > 0): ?>
                <div class="product-rating-container-style mb-3">
                    <div class="product-rating-style d-flex ">
                        <span><?= number_format($avgRating, 1) ?></span>
                        <?= StarRating::widget([
                            'bsVersion' => '5.x',
                            'name' => 'product-rating-style' . $model->id,
                            'value' => $avgRating,
                            'pluginOptions' => [
                                'size' => 'xs',
                                'readonly' => true,
                                'showClear' => false,
                                'showCaption' => false,
                                'hoverEnabled' => false,
                                'displayOnly' => true
                            ]
                        ]) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <div class="m-2 d-flex justify-content-center pb-3">
        <?php if (Yii::$app->user->identity?->isClient): ?>
            <?= Html::a('В корзину', ['/account/account-basket/add', 'product_id' => $model->id], [
                'class' => "btn btn-outline-success w-75 btn-basket-add",
                'data-pjax' => 0
            ]) ?>
        <?php endif ?>
    </div>
</div>