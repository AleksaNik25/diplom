<?php

use app\models\Status;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Order $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$time_order = Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s');
$this->title = "Заказ №" . $model->id . " от " . $time_order;

$status_col = str_replace(' ', '-', $statuses[$model->status_id]['alias']);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex gap-2">
        <?= Html::a('К заказам', ['index'], ['class' => 'btn btn-outline-primary']) ?>
        <?php
        switch ($model->status_id) {
            case $status_order['new']:
                echo Html::a('В обработку', ['change-status', 'order_id' => $model->id, 'status_id' => $status_order["in delivery"]], ['class' => "btn btn-success", "data-method" => "post"]);

                echo  Html::a('Отменить заказ', ['change-status', 'order_id' => $model->id, 'status_id' => $status_order["canceled"]], ['class' => "btn btn-danger", "data-method" => "post"]);
                break;
            case $status_order['in delivery']:
                echo Html::a('Заказ выполнен', ['change-status', 'order_id' => $model->id, 'status_id' => $status_order["finished"]], ['class' => "btn btn-success", "data-method" => "post"]);
        } ?>
    </p>

    <div class="text-black detail-view-container">
        <?= DetailView::widget([
            'options' => ['class' => 'detail-view table-borderless'],
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'created_at',
                    'value' => $time_order,
                ],
                'amount',
                'sum',
                'address',
                'phone',
                [
                    'attribute' => 'date',
                    'value'     => Yii::$app->formatter->asDate($model->date, 'php:d.m.Y'),
                ],
                [
                    'attribute' => 'time',
                    'value'     => Yii::$app->formatter->asTime($model->time, 'php:H:i'),
                ],
                [
                    'attribute' => 'pay_type_id',
                    'value'     => $model->payType->title ?? '—',
                ],
                [
                    'attribute' => 'status_id',
                    'format' => 'html',
                    'value' => "<span class=\"order-status order-{$status_col}\">" . $model->status->title
                ],
            ],
        ]) ?>
    </div>

    <h3 class="text-center mt-5 mb-0">Cостав заказа</h3>

    <?= $this->render('view-order-items', [
        'dataProviderItems' => $dataProviderItems,
        'order' => $order
    ]) ?>

</div>