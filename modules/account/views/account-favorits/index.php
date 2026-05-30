<?php

use app\models\Favorits;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Избранные товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="favorits-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-3 d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['account-order/index'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <?php Pjax::begin([
        'id' => 'favourite-pjax',
        'enablePushState' => false,
        'timeout' => 5000
    ]); ?>

    <?php if ($dataProvider->totalCount): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{summary}\n<div class=\"d-flex justify-content-around flex-wrap gap-2\">{items}</div>\n{pager}",

            'pager' => [
                'class' => LinkPager::class,
            ],
            'itemOptions' => ['class' => 'item'],
            'itemView' => 'item',
        ]) ?>

    <?php else: ?>
        <div class="alert alert-info" role="alert">
            У вас еще нет избранных товаров
        </div>
    <?php endif ?>

    <?php Pjax::end(); ?>

</div>

<?php
$this->registerJsFile('/js/favourite.js', ['depends' => 'yii\web\YiiAsset']);
?>