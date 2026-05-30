<?php

use yii\bootstrap5\Html;

?>

<div class="card my-3" style="width: 30rem;">
    <div class="card-header heder-cart-bg text-light">
        <h5 class="card-title fw-bold text-center"><?= Html::a($model->companyInfo->title, ['view', 'id' => $model->id], ['class' => 'text-dark text-decoration-none text-light'])  ?></h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Контактное лицо: <?= $model->companyInfo->person ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">ИНН: <?= $model->companyInfo->inn ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Юридический адрес: <?= $model->companyInfo->address ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Адрес электронной почты: <?= $model->companyInfo->email ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><span class="fw-bold">Статус: </span><?= $model->approval == 1 ? 'Подтверждена' : 'Ожидает подтверждения' ?></li>
    </ul>
    <div class="d-flex justify-content-around m-2">
        <?= Html::a('Просмотр', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить эту компанию?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
</div>