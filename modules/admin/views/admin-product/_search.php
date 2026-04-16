<?php

use app\models\Status;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AdminProductSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="product-search w-50">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'status_id')->dropDownList(Status::getStatuses(), ['prompt' => 'Выберите статус поиска'])  ?>

    <?= $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'preview') 
    ?>

    <?php // echo $form->field($model, 'care_recommendations') 
    ?>

    <?php // echo $form->field($model, 'price') 
    ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сбросить', 'index', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>