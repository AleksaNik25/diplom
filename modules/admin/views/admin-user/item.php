<?php

use yii\bootstrap5\Carousel;
use yii\bootstrap5\Html;
use yii\helpers\VarDumper;

?>

<div class="card" style="width: 18rem;">
    <div class="card-body">
        <div>
            <h5 class="card-title fw-bold">Пользователь: <?= $model->login ?> </h5>
        </div>
        <hr>
        <div>
            <span class="card-text">Фамилия: <?= $model->surname ?></span>
        </div>
        <div>
            <span class="card-text">Имя: <?= $model->name ?></span>
        </div>
        <div>
            <span class="card-text">Отчество: <?= $model->patronymic ?></span>
        </div>
        <div>
            <span class="card-text">Почтовый адрес: <?= $model->email ?></span>
        </div>
        <div>
            <span class="card-text">Номер телефона: <?= $model->phone ?></span>
        </div>
        <div class="d-flex gap-3 mt-3 justify-content-center">
            <?= Html::a('Просмотр', ['view', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
            <?= Html::a('Утвердить', ['on sale', 'id' => $model->id], ['class' => 'btn btn-outline-success'/* , 'data-method' => 'post' */]) ?>
        </div>
    </div>
</div>