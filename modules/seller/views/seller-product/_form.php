<?php

use app\models\Category;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Product $model */
/** @var yii\widgets\ActiveForm $form */
?>

<p>
    <?= Html::a('Назад', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
</p>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preview')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'care_recommendations')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(Category::getCategory(), ['prompt' => 'Выберите категорию растения']) ?>
    
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <!-- <?php # $form->field($model, 'category_id')->dropDownList($category, ['prompt' => 'Выберите категорию растения']) 
            ?> -->

    <div class="form-group">
        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>