<?php

use app\models\Product;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\controllers\CatalogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Каталог';
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin([
        'id' => 'catalog-pjax',
        'enablePushState' => false,
        'timeout' => 5000
    ]); ?>


    <div class="d-flex align-items-end justify-content-end">
        <!-- <div class="mb-3">
            <?php # $dataProvider->sort->link('price') 
            ?> |
            <?php # $dataProvider->sort->link('title') 
            ?>

        </div> -->
        <div>
            <?php echo $this->render('_search', ['model' => $searchModel]);  ?>
        </div>
    </div>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{summary}\n<div class=\"d-flex justify-content-center flex-wrap gap-3 align-items-stretch\">{items}</div>\n{pager}",
        'summary' => '',
        'pager' => [
            'class' => LinkPager::class,
        ],
        'itemOptions' => ['class' => 'item d-flex'],
        'itemView' => 'item',
    ]) ?>

    <?php Pjax::end(); ?>

</div>

<?php
$this->registerJsFile('/js/catalog.js', ['depends' => 'yii\web\YiiAsset']);
?>