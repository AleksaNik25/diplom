<?php

use app\models\Company;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\SellerCompanySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Мои компании';

Yii::debug($dataProvider->totalCount);
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-3 d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['seller-product/index'], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Добавить компанию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php if ($dataProvider->totalCount): ?>
        
        
    
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
    <?php endif ?>

    <?php Pjax::end(); ?>

</div>