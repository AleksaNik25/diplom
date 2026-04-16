<?php

use yii\bootstrap5\Html;

?>

<div class="card" style="width: 18rem;">
    <div class="card-header">
        <h5 class="card-title fw-bold"><?= $model->companyInfo->title ?></h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">ИНН: <?= $model->companyInfo->inn ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Юридический адрес: <?= $model->companyInfo->address ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Адрес электронной почты: <?= $model->companyInfo->email ?></li>
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
    </div>
</div>
