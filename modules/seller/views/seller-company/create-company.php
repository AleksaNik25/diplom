<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CompanyInfo $model */
/** @var ActiveForm $form */
?>

<p class="mt-3">
    <?= Html::a('Назад', ['index'], ['class' => 'btn btn-outline-primary']) ?>
</p>

<div class="seller-company-create-company">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($modelInfo, 'title')->textInput() ?>

    <?= $form->field($modelInfo, 'inn')->textInput() ?>

    <?= $form->field($modelInfo, 'address')->textInput() ?>

    <?= $form->field($modelInfo, 'email')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>