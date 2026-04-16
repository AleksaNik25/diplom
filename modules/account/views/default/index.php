<?php

use yii\helpers\Html;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-default-index">

    <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Избранные', ['/account/account-favorits'], ['class' => 'btn btn-outline-warning']) ?>
    </p>

    <p>
        <?= Html::a('История заказов', ['/seller/seller-product'], ['class' => 'btn btn-outline-success']) ?>
    </p>

    <p>
        <?= Html::a('Мои отзывы', ['/seller/seller-company'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

</div>