<?php

use app\models\Category;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Category $model */
/** @var app\models\Category[] $subcategories */

$isRoot = $model->parent_id === null;

$rootList = ArrayHelper::map(
    Category::find()->where(['parent_id' => null, 'extend' => null])->all(),
    'id',
    'title'
);
?>

<p>
    <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], ['class' => 'btn btn-outline-primary']) ?>
</p>

<div class="category-form">

    <?php $form = ActiveForm::begin(['id' => 'form-category']); ?>
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?php if ($isRoot): ?>
        <!-- Корневая категория: только название -->
        <div class="col-6 mb-3">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Название') ?>
        </div>

    <?php else: ?>
        <!-- Подкатегория: дропдаун корневой + название + чекбокс extend -->
        <div class="col-6 mb-3">
            <label class="form-label fw-bold">Корневая категория <span class="text-danger">*</span></label>
            <select name="Category[parent_id]" id="root-select" class="form-select" required>
                <option value="">Выберите категорию</option>
                <?php foreach ($rootList as $id => $title): ?>
                    <option value="<?= $id ?>" <?= $model->parent_id == $id ? 'selected' : '' ?>>
                        <?= Html::encode($title) ?>
                    </option>
                <?php endforeach ?>
            </select>
            <div class="invalid-feedback">Необходимо выбрать категорию</div>
        </div>

        <div class="col-6 mb-3">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label('Название') ?>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold d-block">Расширяющая</label>
            <input type="hidden" name="Category[extend]" value="0">
            <input type="checkbox"
                name="Category[extend]"
                class="form-check-input fs-5"
                value="1"
                <?= !empty($model->extend) ? 'checked' : '' ?>>
        </div>

    <?php endif ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>