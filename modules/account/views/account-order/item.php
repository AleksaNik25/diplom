<?php

use app\models\Status;
use yii\bootstrap5\Html;


?>

<div class="card mb-3">
    <h5 class="card-header heder-cart-bg text-light"><?= "Заказ №" . $model->id . " от " . Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s') ?></h5>
    <div class="card-body d-flex flex-column flex-wrap gap-2">
        <div><span class="text-secondary">Количество товаров: </span> <span class="fw-bold"><?= $model->amount ?></span></div>
        <div><span class="text-secondary">Сумма заказа:</span> <span class="fw-bold"><?= $model->sum ?></span></div>
        <div>
            <span class="text-secondary">Статус заказа: </span>
            <span class="order-status order-<?= str_replace(' ', '-', $statuses[$model->status_id]['alias']) ?>"> <?= $statuses[$model->status_id]['title'] ?></span>
        </div>
    </div>
    <div class="d-flex justify-content-end gap-2 p-2">
        <?= Html::a('Состав заказа', ['view', 'id' => $model->id], ['class' => "btn btn-primary"]) ?>
    </div>

</div>