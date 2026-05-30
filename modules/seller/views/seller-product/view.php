<?php

use yii\bootstrap5\Carousel;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= $model->status->alias !== 'arhived'
            ? Html::a('В архив', ['change-status', 'id' => $model->id, 'status' => 'arhived'], ['class' => 'btn btn-primary'])
            : ''
        ?>
        <?= $model->status->alias == 'arhived'
            ? Html::a('Предложить опубликовать', ['change-status', 'id' => $model->id, 'status' => 'check'], ['class' => 'btn btn-primary'])
            : ''
        ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-outline-success',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
    $images = array_map(function ($item) use ($model) {
        return "<img src=\"/img/$item->photo\" alt=\"$model->title\" style=\"max-width: 30rem;  min-height: 450px; max-height: 450px;\" class=\"img-cart-style\">";
    }, $model->productImages);
    ?>

    <div class="container mt-4">
        <div class="row g-5 d-flex gap-5 justify-content-center">
            <div class="card img-view" style="max-width: 30rem; min-width: 30rem;">
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
            <div class="col-sm-6 product-info">

                <h1><?= Html::encode($this->title) ?></h1>

                <p class="product-preview lead">
                    <?= $model->preview ?>
                </p>

                <div class="product-category lead mb-3">
                    <?php
                    $cats = $model->categories;
                    $regular = array_filter($cats, fn($c) => !$c->extend);
                    $extended = array_filter($cats, fn($c) => $c->extend);
                    ?>
                    <?php if (!empty($regular)): ?>
                        <div class="mb-2">
                            <?php if (!empty($regular)): ?>
                                <div class="mb-1">
                                    <span>
                                        <?= Html::encode(implode(' ✦ ', \yii\helpers\ArrayHelper::getColumn($regular, 'title'))) ?>
                                    </span>
                                </div>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                    <?php if (!empty($extended)): ?>
                        <div class="mb-1">
                            <span class="text-secondary">
                                <?= Html::encode(implode(' ✦ ', \yii\helpers\ArrayHelper::getColumn($extended, 'title'))) ?>
                            </span>
                        </div>
                    <?php endif ?>
                </div>

                <div class="product-price h2 mb-3">
                    <?= Yii::$app->formatter->asDecimal($model->price, 2) ?> ₽
                </div>
            </div>
        </div>


        <div class="my-3 d-sm-flex gap-5 justify-content-center">
            <div class="product-care_recommendations lead flex-wrap col-sm-8 my-3">
                <?= $model->care_recommendations ?>
            </div>

            <!-- <div class="border px-4" style="border-radius: 10px;"> -->
            <div class="product-star_rating lead flex-wrap my-3">
                <?= $this->render("star", ["model" => $model]) ?>
            </div>
            <!-- </div> -->
        </div>


        <div class="my-3">
            <h4>Отзывы о товаре</h4>
        </div>

        <?php Pjax::begin([
            'id' => 'product-comments-pjax',
            'enablePushState' => false,
            'timeout' => 5000
        ]); ?>

        <?php if ($dataProvider->totalCount): ?>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'item'],
                'layout' => '{pager}<div class="d-flex flex-column gap-3">{items}</div>{pager}',
                'itemView' => 'item_comment',
                'viewParams' => [
                    'product' => $model,
                ],
                'pager' => [
                    'class' => LinkPager::class,
                ]

            ]) ?>
        <?php else: ?>
            <div class="alert alert-primary" role="alert">
                У этого товара еще нет отзывов.
            </div>
        <?php endif ?>

        <?php Pjax::end(); ?>
    </div>
</div>


<?php
Modal::begin([
    'id' => 'modal-comment',
    'title' => 'Поделитесь своими впечатлениями о товаре',
    'size' => Modal::SIZE_LARGE
]);
?>
<div id="modal-comment-body">
    <?= $this->render("@app/modules/account/views/account-comment/create", ['model' => $model_comment]) ?>
</div>
<?php
Modal::end();

$replyPlaceholder = '';
if (Yii::$app->user->identity?->isSeller) {
    $replyPlaceholder = 'Ответ на отзыв пользователя...';
} elseif (Yii::$app->user->identity?->isClient) {
    $replyPlaceholder = 'Ответ на комментарий продавца...';
}

$this->registerJs("window.commentReplyPlaceholder = " . json_encode($replyPlaceholder) . ";");

$this->registerJsFile("/js/comment.js", ['depends' => JqueryAsset::class]);
$this->registerJsFile("/js/product.js", ["depends" => JqueryAsset::class]);
$this->registerJsFile("/js/favourite.js", ["depends" => JqueryAsset::class]);
?>