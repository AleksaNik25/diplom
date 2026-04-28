<?php

use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

$disabled = Yii::$app->user->isGuest ? 'disabled' : '';
$images = array_map(function ($item) use ($model) {
    return  "<img src=\"/img/$item->photo\" alt=\"$model->title\" style=\"max-width: 18rem; min-height: 250px; max-height: 250px; \" class=\"img-cart-style\">";
}, $model->productImages);

$viewUrl = Url::to(['view', 'id' => $model->id]);
?>



<div class="card mt-3 cart-style">
    <a href="<?= $viewUrl ?>" class="text-decoration-none">
        <div class="img-cart-style">
            <?= Carousel::widget([
                'items' => $images,
                'options' => [
                    'class' => 'carousel slide carousel-fade',
                    'data-bs-ride' => "carousel",
                    'data-bs-interval' => '7000',
                    'style' => 'max-height: 400px;',
                ],
            ]);
            ?>
        </div>
    </a>

    <div class="card-body">
        <div>
            <h5 class="card-title fw-bold">
                <a href="<?= $viewUrl ?>" class="text-decoration-none text-dark">
                    <?= Html::encode($model->title) ?>
                </a>
            </h5>
            <div>
                <span class="card-text"><?= $model->category->title ?></span>
            </div>
            <div>
                <p class="card-text text-end"><?= $model->price ?><span> ₽</span>
            </div>
        </div>
    </div>

    <div class="m-2">
        <?php if (Yii::$app->user->identity?->isClient): ?>
            <?= Html::a('В корзину', ['/account/account-basket/add', 'product_id' => $model->id], [
                'class' => "btn btn-outline-primary w-100 $disabled",
                'data-pjax' => 0
            ]) ?>
        <?php endif ?>
    </div>
</div>