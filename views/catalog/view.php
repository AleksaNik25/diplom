<?php

use kartik\rating\StarRating;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <p class="my-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', $_SERVER['HTTP_REFERER'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <?php
    $images = array_map(function ($item) use ($model) {
        return "<img src=\"/img/$item->photo\" alt=\"$model->title\" style=\"max-width: 30rem;  min-height: 450px; max-height: 450px;\" class=\"img-cart-style\">";
    }, $model->productImages);

    $disabled = Yii::$app->user->isGuest ? 'disabled' : '';
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

                <?php
                $favorits_id = $model?->favorits ? $model?->favorits[0]?->id : false;
                $favorits_color = $favorits_id ? "text-danger" : "text-secondary";
                ?>
                <div class="d-flex align-items-center justify-content-between">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity?->isClient): ?>
                        <i class="fas fa-heart icon-favourite fs-2 <?= $favorits_color ?>"
                            style="cursor: pointer;"
                            data-url="<?= $favorits_id
                                            ? Url::to(['/account/account-favorits/remove', 'id' => $favorits_id])
                                            : Url::to(['/account/account-favorits/add', 'product_id' => $model->id]) ?>">
                        </i>
                    <?php endif ?>
                </div>

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
                            <span class="text-success">
                                <?= Html::encode(implode(' ✦ ', \yii\helpers\ArrayHelper::getColumn($extended, 'title'))) ?>
                            </span>
                        </div>
                    <?php endif ?>
                </div>

                <p class="product-preview lead">
                    <?= $model->preview ?>
                </p>

                <div class="product-price fw-bold my-3">
                    <?= Yii::$app->formatter->asDecimal($model->price, 2) ?> ₽
                </div>

                <div class="my-4">
                    <?php if (Yii::$app->user->identity?->isClient): ?>
                        <?= Html::a('В корзину', ['/account/account-basket/add', 'product_id' => $model->id], [
                            'class' => "px-4 fs-5 btn btn-primary $disabled",
                            'id' => 'btn-add-to-cart',
                        ]) ?>
                    <?php endif ?>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?= Html::a('В корзину', ['/site/login'], [
                            'class' => "px-4 fs-5 btn btn-primary",
                        ]) ?>
                    <?php endif ?>
                </div>
            </div>
        </div>


        <div class="my-3 d-sm-flex gap-5 justify-content-center">
            <div class="product-care_recommendations lead flex-wrap col-sm-8 my-3">
                <?= $model->care_recommendations ?>
            </div>

            <div class="product-star_rating lead flex-wrap my-3">
                <?php if (Yii::$app->user->identity?->isClient): ?>
                    <div class="rating-block rounded">
                        <?php if ($model->canLeaveRating(Yii::$app->user->id)): ?>
                            <div class="alert alert-success alert-stars d-none text-center">
                                Рейтинг успешно поставлен!
                            </div>
                            <?php $form = ActiveForm::begin([]) ?>
                            <?php $url = Url::to(["stars", "id" => $model->id]) ?>
                            <?= $form->field($model, 'user_stars', ["options" => ["data-url" => $url]])
                                ->label('Ваша оценка товара')
                                ->widget(StarRating::class, [
                                    'bsVersion' => '5.x',
                                    'pluginOptions' => [
                                        'readonly' => false,
                                        'showClear' => false,
                                        'showCaption' => false,
                                        'min' => 0,
                                        'max' => 5,
                                        'step' => 1,
                                        'hoverEnabled' => true,
                                        'displayOnly' => false,
                                    ],
                                ]);
                            ?>
                            <?php ActiveForm::end() ?>
                            <?= $this->render("star", ["model" => $model]) ?>
                        <?php elseif ($stars > 0): ?>
                            <h4>Ваша оценка товара</h4>
                            <?= StarRating::widget([
                                'bsVersion' => '5.x',
                                'name'  => 'user_stars_readonly',
                                'value' => $stars,
                                'pluginOptions' => [
                                    'readonly' => true,
                                    'showClear' => false,
                                    'showCaption' => false,
                                    'displayOnly' => true,
                                ],
                            ]) ?>
                            <?= $this->render("star", ["model" => $model]) ?>
                        <?php else: ?>
                            <?= $this->render("star", ["model" => $model]) ?>
                            <div class="alert alert-primary mt-2">
                                Оценить товар можно после получения заказа.
                            </div>
                        <?php endif ?>
                    </div>
                <?php else: ?>
                    <?= $this->render("star", ["model" => $model]) ?>
                <?php endif ?>
            </div>
        </div>


        <div class="my-3">
            <h4>Отзывы о товаре</h4>
        </div>

        <div class="d-flex justify-content-start mb-3">
            <?php if (Yii::$app->user->identity?->isClient): ?>
                <?= $model->canLeaveComment(Yii::$app->user->id)
                    ? Html::a('Оставить комментарий', ['/account/account-comment/write', 'product_id' => $model->id], ['class' => 'btn btn-primary btn-comment'])
                    : ""
                ?>
            <?php endif ?>
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
                У этого товара еще нет отзывов. Будте первыми!
            </div>
        <?php endif ?>

        <?php Pjax::end(); ?>
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