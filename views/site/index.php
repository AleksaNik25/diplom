<?php

/** @var yii\web\View $this */

use app\models\Product;
use kartik\rating\StarRating;
use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\helpers\Url;
use yii\widgets\ListView;

$products = Product::find()
    ->innerJoin('status', 'status.id = product.status_id')
    ->where(['>', 'product.estimation', 0])
    ->andWhere(['status.alias' => 'on sale'])
    ->limit(4)
    ->orderBy(['product.estimation' => SORT_DESC])
    ->with(['productImages', 'categories'])
    ->all();

$productChunks = array_chunk($products, 4);


?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Окружи себя природой</h1>

        <p class="lead">Некоторый текст, о том какой это крутой и очень важный и нужный маркетплейс, <br> по продаже растений и всяческих приблуд для ухода за ними</p>

        <p><?= Html::a('Наши товары', ['/catalog'], ['class' => 'btn btn-success']) ?></p>
    </div>


    <div class="container d-lg-block d-none">
        <h1 class="title-style text-center">Популярные товары</h1>
        <div class=" justify-content-between flex-wrap gap-2">
            <?php foreach ($productChunks as $index => $chunk): ?>
                <div class=" <?= $index === 0 ? 'active' : '' ?>">
                    <div class="d-flex justify-content-between">
                        <?php foreach ($chunk as $product): ?>
                            <div class="card mt-4 cart-style">

                                <?php
                                $images = array_map(function ($item) use ($product) {
                                    return  "<img src=\"/img/$item->photo\" alt=\"$product->title\" style=\"max-width: 18rem; min-height: 286.4px; max-height: 286.4px; \" class=\"img-cart-style\">";
                                }, $product->productImages);

                                $viewUrl = Url::to(['catalog/view', 'id' => $product->id]);

                                $favorits_id = $product?->favorits ? $product?->favorits[0]?->id : false;
                                $favorits_color = $favorits_id
                                    ? "text-danger"
                                    : "text-white";
                                ?>

                                <div class="icon-place">
                                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity?->isClient):
                                    ?>
                                        <i
                                            class="fas fa-heart icon-favourite fs-4 <?= $favorits_color
                                                                                    ?>"
                                            data-url="<?= $favorits_id
                                                            ? Url::to(['/account/account-favorits/remove', 'id' => $favorits_id])
                                                            : Url::to(['/account/account-favorits/add', 'product_id' => $product->id])
                                                        ?>">
                                        </i>
                                    <?php endif ?>
                                </div>

                                <a href="<?= $viewUrl
                                            ?>" class="text-decoration-none">
                                    <div class="img-cart-style">
                                        <?= Carousel::widget([
                                            'items' => $images,
                                            'options' => [
                                                'class' => 'carousel slide carousel-fade',
                                                'data-bs-interval' => '7000',
                                                'style' => 'max-height: 400px;',
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </a>

                                <div class="card-body pb-2">
                                    <div class="text-center pb-2">
                                        <h5 class="card-title fw-bold">
                                            <div class="d-flex justify-content-center">
                                                <?= Html::a($product->title, $viewUrl, ['class' => 'text-decoration-none text-dark', 'data-pjax' => 0]) ?>
                                            </div>
                                        </h5>
                                        <div>
                                            <?php
                                            $mainCat = null;
                                            foreach ($product->categories as $cat) {
                                                if (!$cat->extend) {
                                                    $mainCat = $cat;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <span class="card-text text-secondary"><?= $mainCat ? Html::encode($mainCat->title) : '—' ?></span>
                                        </div>
                                        <div>
                                            <p class="card-text"><?= $product->price ?><span> ₽</span>
                                        </div>

                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <?php $avgRating = $product->getAverageRating(); ?>
                                        <?php if ($avgRating > 0): ?>
                                            <div class="product-rating-container-style mb-3">
                                                <div class="product-rating-style d-flex ">
                                                    <span><?= number_format($avgRating, 1) ?></span>
                                                    <?= StarRating::widget([
                                                        'bsVersion' => '5.x',
                                                        'name' => 'product-rating-style' . $product->id,
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>