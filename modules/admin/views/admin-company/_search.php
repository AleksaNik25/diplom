<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\AdminCompanySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="company-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'd-flex align-items-end gap-3',
            'id' => 'form-search'
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'person')->textInput() ?>

    <?= $form->field($model, 'inn')->textInput() ?>

    <?= $form->field($model, 'address')->textInput() ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-redo-alt"></i>', 'index', ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>