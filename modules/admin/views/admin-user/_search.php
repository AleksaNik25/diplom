<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'd-flex align-items-end gap-3',
            'id' => 'form-search'
        ],
    ]); ?>

    <?= $form->field($model, 'login') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') 
    ?>

    <?php // echo $form->field($model, 'role') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-redo-alt"></i>', 'index', ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>