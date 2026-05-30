<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CompanyInfo $model */
/** @var ActiveForm $form */
?>

<p class="mt-3">
    <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], ['class' => 'btn btn-outline-primary']) ?>
</p>

<div class="seller-company-create-company w-50">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($modelInfo, 'title')->textInput() ?>
    
    <?= $form->field($modelInfo, 'person')->textInput() ?>
    
    <?= $form->field($modelInfo, 'inn')->textInput() ?>
    
    <?= $form->field($modelInfo, 'address')->textInput() ?>
    
    <?= $form->field($modelInfo, 'email')->textInput() ?>

    <?= $form->field($company, 'docFiles[]')
        ->fileInput(['multiple' => true, 'accept' => 'image/*,.pdf'])
        ->label('Документы компании') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>