<?php

use yii\bootstrap5\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\LinkPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\Comment $model */

$this->title = "Мои отзывы";
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-3 d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['account-order/index'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

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
            'itemView' => 'item',
            'pager' => [
                'class' => LinkPager::class,
            ]

        ]) ?>

    <?php else: ?>
        <div class="alert alert-primary" role="alert">
            Вас еще нет оставляли комментариев
        </div>
    <?php endif ?>

    <?php Pjax::end(); ?>

</div>