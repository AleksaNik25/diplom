<?php

use yii\helpers\Html;

$this->title = 'Панель администратора';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать категорию', ['/admin/admin-category'], ['class' => 'btn btn-outline-warning']) ?>
    </p>

    <p>
        <?= Html::a('Проверить товаров', ['/admin/admin-product'], ['class' => 'btn btn-outline-success']) ?>
    </p>

    <p>
        <?= Html::a('Подтверждение продавца', ['/admin/admin-user'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

</div>