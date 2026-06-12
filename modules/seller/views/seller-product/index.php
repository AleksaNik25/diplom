<?php

use app\models\Product;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\seller\models\SellerProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Личный кабинет продавца';

?>

<div class="product-index d-flex gap-3 mb-4 mt-4">

    <p>
        <?= Html::a('Мои компании', ['/seller/seller-company'], ['class' => 'btn btn-primary']) ?>
    </p>

</div>

<?php
$this->title = 'Мои товары';
?>

<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (\app\models\Company::isCurrentSellerApproved()): ?>
        <p class="mt-3 d-flex gap-2">
            <?= Html::a('Создать товар', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php else: ?>
        <div class="alert alert-warning ">
            Добавление товаров недоступно — ваша компания ещё не подтверждена администратором.
        </div>
    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{summary}\n<div class=\"d-flex justify-content-around flex-wrap gap-2\">{items}</div>\n{pager}",

        'pager' => [
            'class' => LinkPager::class,
        ],
        'itemOptions' => ['class' => 'item'],
        'itemView' => 'item',
        'summary' => '',
    ]) ?>

    <?php Pjax::end(); ?>

</div>

<?php
$this->registerJsFile('/js/catalog.js', ['depends' => 'yii\web\YiiAsset']);
?>