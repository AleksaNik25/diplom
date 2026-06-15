<?php

use app\models\PayType;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\widgets\ListView;

/** @var yii\web\View $this */
/** @var app\models\Order $model */

$minDate = date('Y-m-d', strtotime('+1 day'));

if (!$model->date) $model->date = $minDate;
if (!$model->time) $model->time = '10:00';
?>

<div class="mt-3">
    <?= Html::a('Продолжить покупки', ['/catalog'], ['class' => 'btn btn-outline-primary', 'data-pjax' => 0]) ?>
</div>

<div class="order-form mt-3">

    <h3 class="text-center py-2">Данные получателя</h3>

    <?php $form = ActiveForm::begin(['action' => ['create', 'basket_id' => $basket->id]]); ?>

    <div class="row g-3">

        <?= $form->field($model, 'address')->textInput([
            'maxlength'   => true,
            'placeholder' => 'г. Санкт-Петербург, наб. Смоленки, д. 1, кв. 1',
        ]) ?>

        <?= $form->field($model, 'phone')->widget(
            \yii\widgets\MaskedInput::class,
            [
                'mask' => '+7(999)-999-99-99',
                'options' => ['placeholder' => '+7(999)-999-99-99'],
            ]
        ) ?>

        <div class="d-flex justify-content-start gap-3">
            <?= $form->field($model, 'date')->textInput([
                'type' => 'date',
                'min'  => $minDate,
            ]) ?>

            <?= $form->field($model, 'time')->textInput([
                'type' => 'time',
                'min'  => '10:00',
                'max'  => '20:30',
                'step' => '900',
            ]) ?>
        </div>

        <?= $form->field($model, 'pay_type_id')->dropDownList(
            PayType::getPayType(),
            ['prompt' => 'Выберите способ оплаты:']
        ) ?>

    </div>
</div>

<h3 class="text-center pt-4">Состав заказа</h3>

<div>
    <?= ListView::widget([
        'dataProvider' => $dataProviderItems,
        'itemOptions'  => ['class' => 'item'],
        'itemView'     => 'item-order',
        'pager'        => ['class' => LinkPager::class],
        'summary'      => '',
    ]) ?>

    <div class="border-white border-top border-2 py-3 order-total fw-bold fs-3">
        <div class="row align-items-start">
            <div class="col-1 offset-1">Итого:</div>
            <div class="col-2 text-center"><?= $basket->amount ?> шт.</div>
            <div class="col-2 offset-5 text-end">
                <?= Yii::$app->formatter->asDecimal($basket->sum, 2) ?> ₽
                <div class="text-end mt-3">
                    <?= Html::submitButton('Подтвердить заказ', ['class' => 'btn btn-success']) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>