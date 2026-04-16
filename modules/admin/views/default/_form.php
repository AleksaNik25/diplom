<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<p>
    <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
</p>

<div class="product-form w-50">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'user_id')->textInput() ?> -->

    <?= $form->field($model, 'category_id')->textInput() ?>

    <!-- <?= $form->field($model, 'preview')->textarea(['rows' => 6]) ?>  это для parent_id -->

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>