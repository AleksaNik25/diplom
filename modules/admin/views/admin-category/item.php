<?php

use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\VarDumper;
?>

<div class="card" style="width: 18rem;">
    <div class="card-header">
        <h5 class="card-title fw-bold"><?= $model->title ?></h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><?= $model->parent_id ?></li>
    </ul>
    <div class="d-flex justify-content-around m-2">
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

        <!-- <?php # $model->status->alias !== 'confirmed' && $model->status->alias == 'check'
            # ?  Html::a('Опубликовать', ['change-status', 'id' => $model->id, 'status' => 'confirmed'], ['class' => 'btn btn-outline-success'])
            # : ''
        # ?> -->
    </div>
</div>