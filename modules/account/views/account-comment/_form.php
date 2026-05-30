<?php

use kartik\rating\StarRating;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Comment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-comment',
        'action' => '/account/account-comment/write?product_id=' . $model->product_id,
        'options' => [
            'data-pjax' => true
        ]
    ]); ?>

    <?php if (!$model->parent_id): ?>
        <div class="mb-4">
            <label class="form-label fw-bold">Ваша оценка</label>
            <?= $form->field($model, 'user_stars')->widget(StarRating::classname(), [
                'bsVersion' => '5.x',
                'pluginOptions' => [
                    'size' => 'lg',
                    'showClear' => false,
                    'showCaption' => false,
                    'step' => 1,
                ],
            ])->label(false) ?>
        </div>
    <?php endif ?>

    <?php if (!$model->parent_id): ?>
        <?= $form->field($model, 'text')->textarea([
            'rows' => 6,
            'class' => 'form-control',
            'placeholder' => 'Напишите ваш отзыв о товаре...'
        ]) ?>
    <?php elseif (Yii::$app->user->identity?->isSeller) : ?>
        <?= $form->field($model, 'text')->textarea([
            'rows' => 6,
            'class' => 'form-control',
            'placeholder' => 'Ответ на отзыв пользователя...'
        ]) ?>
    <?php elseif (Yii::$app->user->identity?->isClient && $model->parent_id) : ?>
        <?= $form->field($model, 'text')->textarea([
            'rows' => 6,
            'class' => 'form-control',
            'placeholder' => 'Ответ на комментарий продавца...'
        ]) ?>
    <?php endif ?>

    <?= $form->field($model, 'parent_id')->hiddenInput(['value' => $model->parent_id])->label(false) ?>

    <div class="form-group d-flex justify-content-end gap-3">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Отменить</button>
        <button type="submit" class="btn btn-success">Сохранить</button>

    </div>

    <?php ActiveForm::end(); ?>

</div>