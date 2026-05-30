<?php

use app\models\Status;
use yii\bootstrap5\Html;


?>

<div class="card mb-3">
    <h5 class="card-header heder-cart-bg text-light"><?= "Заказ №" . $model->id . " от " . Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i:s') ?></h5>
    <div class="card-body d-flex flex-column gap-2">
        <div><span class="text-secondary fw-bold">Клиент: </span> <span class="fw-bold"><?= $model->user->login ?></span></div>
        <div><span class="text-secondary">Количество товаров: </span> <span class="fw-bold"><?= $model->amount ?></span></div>
        <div><span class="text-secondary">Сумма заказа:</span> <span class="fw-bold"><?= $model->sum ?></span></div>
        <div>
            <span class="text-secondary">Статус заказа: </span>
            <span class="order-status order-<?= str_replace(' ', '-', $statuses[$model->status_id]['alias']) ?>"> <?= $statuses[$model->status_id]['title'] ?></span>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div class="d-flex gap-2 p-2">
            <?= Html::a('Состав заказа', ['view', 'id' => $model->id], ['class' => "btn btn-primary"]) ?>
        </div>

        <div class="d-flex justify-content-end gap-2 p-2">
            <?php
            switch ($model->status_id) {
                case $status_order['new']:
                    echo Html::a('В обработку', ['change-status', 'order_id' => $model->id, 'status_id' => $status_order["in delivery"]], ['class' => "btn btn-success", "data-method" => "post"]);

                    echo  Html::a('Отменить заказ', ['change-status', 'order_id' => $model->id, 'status_id' => $status_order["canceled"]], ['class' => "btn btn-danger", "data-method" => "post"]);
                    break;
                case $status_order['in delivery']:
                    echo Html::a('Заказ выполнен', ['change-status', 'order_id' => $model->id, 'status_id' => $status_order["finished"]], ['class' => "btn btn-success", "data-method" => "post"]);
            } ?>
        </div>
    </div>
</div>