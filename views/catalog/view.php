<?php

use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <?php
    $images = array_map(function ($item) use ($model) {
        return "<img src=\"/img/$item->photo\" alt=\"$model->title\" style=\"max-width: 30rem;  min-height: 450px; max-height: 450px;\" class=\"img-cart-style\">";
    }, $model->productImages);

    $disabled = Yii::$app->user->isGuest ? 'disabled' : '';
    // VarDumper::dump($images, 10, true);
    ?>

    <div class="d-flex gap-3">
        <div class="card" style="max-width: 30rem; min-width: 30rem;">
            <div class="img-cart-style">
                <?= Carousel::widget([
                    'items' => $images,
                    'options' => [
                        'class' => 'carousel slide carousel-fade',
                        'data-bs-ride' => "carousel",
                        'data-bs-interval' => '7000',
                        'style' => 'max-height: 600px;',
                    ],
                ]);
                ?>
            </div>
        </div>

        <div>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'title',
                        'value' => $model->title,
                    ],
                    [
                        'attribute' => 'preview',
                        'value' => $model->preview,
                    ],
                    [
                        'attribute' => 'category_id',
                        'value' => $model->category->title,
                    ],
                    [
                        'attribute' => 'price',
                        'value' => $model->price,
                    ],
                    [
                        'attribute' => 'care_recommendations',
                        'value' => $model->care_recommendations,
                    ],

                ],
            ]) ?>

            <div class="d-flex justify-content-end">
                <?php if (Yii::$app->user->identity?->isClient): ?>
                    <?= Html::a('В корзину', ['/account/account-basket/add', 'product_id' => $model->id], [
                        'class' => "btn btn-outline-primary $disabled",
                        'data-pjax' => 0
                    ]) ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>