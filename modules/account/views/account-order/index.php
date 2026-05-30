<?php

use app\models\Order;
use yii\bootstrap5\LinkPager;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountOrderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
?>

<?php

$this->title = 'Личный кабинет';

?>
<div class="account-default-index d-flex gap-3 mb-4 mt-4">

    <?= Html::a('Избранные', ['/account/account-favorits'], ['class' => 'btn btn-danger']) ?>

    <?= Html::a('Мои отзывы', ['/account/account-comment/view'], ['class' => 'btn btn-primary']) ?>

</div>

<div class="order-index">

    <?php
    $this->title = 'Мои заказы';
    ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if ($dataProvider->totalCount): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => fn($model) => $this->render('item', [
                'model' => $model,
                'statuses' => $statuses,
                'status_order' => $status_order
            ]),
            'pager' => [
                'class' => LinkPager::class
            ],
        ]) ?>

    <?php else: ?>
        <div class="alert alert-info" role="alert">
            У вас еще нет заказов
        </div>
    <?php endif ?>

    <?php Pjax::end(); ?>

</div>