<?php

use app\models\Product;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\controllers\CatalogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin([
        'id' => 'catalog-pjax',
        'enablePushState' => false,
        'timeout' => 5000
    ]); ?>


    <div class="d-flex align-items-end justify-content-between">
        <div class="mb-3">
            <?= $dataProvider->sort->link('price') ?> |
            <?= $dataProvider->sort->link('title') ?>

        </div>
        <div>
            <?php echo $this->render('_search', ['model' => $searchModel]);  ?>
        </div>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{summary}\n<div class=\"d-flex justify-content-around flex-wrap gap-2\">{items}</div>\n{pager}",

        'pager' => [
            'class' => LinkPager::class,
        ],
        'itemOptions' => ['class' => 'item'],
        'itemView' => 'item',
    ]) ?>

    <?php Pjax::end(); ?>



</div>