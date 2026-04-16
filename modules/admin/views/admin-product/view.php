<?php

use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои растения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex gap-3">
        <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>

        <?= $model->status->alias !== 'on sale'
            ? Html::a('Опубликовать', ['change-status', 'id' => $model->id, 'status' => 'on sale'], ['class' => 'btn btn-outline-success'])
            : ''
        ?>

        <!-- <?= $model->status->alias === 'on sale'
                    ? Html::a('Архивировать', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-warning'])
                    : ''
                ?> -->

        <!-- <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?> -->
    </p>

    <?php
    $images = array_map(function ($item) use ($model) {
        return "<img src=\"/img/$item->photo\" alt=\"$model->title\" style=\"max-width: 30rem;  min-height: 450px; max-height: 450px;\" class=\"img-cart-style\">";
    }, $model->productImages);
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

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'user_id',
                    'value' => $model->user->login,
                ],
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
                [
                    'attribute' => 'status_id',
                    'value' => $model->status->title,
                ],

            ],

        ]) ?>
    </div>
</div>