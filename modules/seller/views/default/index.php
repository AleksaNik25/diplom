<?php

use yii\helpers\Html;

$this->title = 'Личный кабинет продавца';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать категорию', ['/seller/seller-category'], ['class' => 'btn btn-outline-warning']) ?>
    </p>

    <p>
        <?= Html::a('Создать товар', ['/seller/seller-product'], ['class' => 'btn btn-outline-success']) ?>
    </p>

    <p>
        <?= Html::a('Добавить компанию', ['/seller/seller-company'], ['class' => 'btn btn-outline-primary']) ?>
    </p>



</div>