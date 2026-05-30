<?php

use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\VarDumper;

?>

<div class="card my-3" style="width: 30rem;">
    <div class="card-header heder-cart-bg text-light">
        <h5 class="card-title fw-bold text-center">Пользователь: <?= Html::a($model->login, ['view', 'id' => $model->id], ['class' => ' text-light text-decoration-none'])  ?></h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Фамилия: <?= $model->surname ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Имя: <?= $model->name ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Отчество: <?= $model->patronymic ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Почтовый адрес: <?= $model->email ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Номер телефона: <?= $model->phone ?></li>
    </ul>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><span class="fw-bold">Статус: </span><?= $model->userLE->approval == 1 ? 'Подтвержден' : 'Ожидает подтверждения' ?></li>
    </ul>
    <div class="d-flex justify-content-around m-2">
        <?= Html::a('Просмотр', ['view', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if ($model->userLE): ?>
            <?php if ($model->userLE->approval == 0): ?>
                <?= Html::a('Утвердить',     ['change-status', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Заблокировать', ['change-status', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
            <?php elseif ($model->userLE->approval == 1): ?>
                <?= Html::a('Заблокировать', ['change-status', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
            <?php elseif ($model->userLE->approval == 2): ?>
                <span class="badge bg-danger align-self-center">Заблокирован</span>
                <?= Html::a('Разблокировать', ['change-status', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>