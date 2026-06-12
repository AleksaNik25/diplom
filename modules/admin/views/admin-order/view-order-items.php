<?php

use yii\widgets\ListView;
?>
<div class="border-white border-bottom border-2 py-3 order-total fw-bold fs-3">
    <div class="row align-items-start">
        <div class="col-1 offset-1">
            Итого:
        </div>

        <div class="col-2 text-center">
            <?= $order->amount ?> шт.
        </div>

        <div class="col-2 offset-5 text-end">
            <?= Yii::$app->formatter->asDecimal($order->sum, 2) ?> ₽
        </div>
    </div>
</div>

<?= ListView::widget(
    [
        'dataProvider' => $dataProviderItems,
        'itemOptions' => ['class' => 'item'],
        'layout' => "{items}",
        'itemView' => 'item-order'
    ],
)

?>