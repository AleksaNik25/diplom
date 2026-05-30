<?php

use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\Order $model */
$time_order = Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s');

$status_col = str_replace(' ', '-', $statuses[$model->status_id]['alias']);

$this->title = "Заказ №" . $model->id;
\yii\web\YiiAsset::register($this);


?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('К заказам', ['index'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <?= DetailView::widget([
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

    <h3>Cостав заказа</h3>

    <?= $this->render('view-order-items', ['dataProviderItems' => $dataProviderItems, 'order' => $model,]) ?>

</div>