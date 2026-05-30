<?php

use kartik\rating\StarRating;
use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

$mpt = $model->product->title;
$images = array_map(function ($item) use ($model, $mpt) {
    return  "<img src=\"/img/$item->photo\" alt=\"$mpt\" style=\"max-width: 18rem; min-height: 286.4px; max-height: 286.4px; \" class=\"img-cart-style\">";
}, $model->product->productImages);

$viewUrl = Url::to(['/catalog/view', 'id' => $model->product->id]);

$favorits_id = $model->id;
$favorits_color = "text-danger";
?>



<div class="card mt-3 cart-style">
    <div class="img-cart-style">
        <?= Html::a(
            Carousel::widget([
                'items' => $images,
                'options' => [
                    'class' => 'carousel slide carousel-fade',
                    'data-bs-interval' => '7000',
                    'style' => 'max-height: 286.4px;',
                ],
            ]),
            $viewUrl,
            ['class' => 'text-decoration-none']
        ); ?>
    </div>

    <div class="icon-place">
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity?->isClient): ?>
            <i
                class="fas fa-heart icon-favourite fs-4 <?= $favorits_color ?>"
                data-url="<?= Url::to(['/account/account-favorits/remove', 'id' => $favorits_id]) ?>">
            </i>
        <?php endif ?>
    </div>

    <div class="card-body pb-0">
        <div class="text-center pb-2">
            <h5 class="card-title fw-bold">
                <?= Html::a(
                    Html::encode($model->product->title),
                    $viewUrl,
                    ['class' => 'text-decoration-none text-dark d-block fw-bold mb-2']
                ) ?>
            </h5>
            <div>
                <?php
                $mainCat = null;
                foreach ($model->product->categories as $cat) {
                    if (!$cat->extend) {
                        $mainCat = $cat;
                        break;
                    }
                }
                ?>
                <span class="card-text text-secondary"><?= $mainCat ? Html::encode($mainCat->title) : '—' ?></span>
            </div>

            <div>
                <p class="card-text"><?= $model->product->price ?><span> ₽</span>
            </div>
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