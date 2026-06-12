<?php

use app\models\Category;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Category $model */
/** @var app\models\Category[] $subcategories */

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

	<div class="col-6 mb-3">
		<label class="form-label fw-bold">Категория</label>
		<select name="Subcategory[parent_id]" id="root-select" class="form-select" required>
			<option value="">Выберите категорию</option>
			<?php foreach ($rootList as $id => $title): ?>
				<option value="<?= $id ?>" <?= $model->parent_id == $id ? 'selected' : '' ?>>
					<?= Html::encode($title) ?>
				</option>
			<?php endforeach ?>
		</select>
		<div class="invalid-feedback">Необходимо выбрать категорию</div>
	</div>

	<hr>
	<p class="fw-bold">Подкатегории</p>

	<div id="block-subcategory" class="border p-2 mb-3 block-subcategory box-bg">
		<?php foreach ($subcategories as $key => $subcategory): ?>
			<div class="border p-3 my-3 mx-3 item-subcategory col-8" data-index="<?= $key ?>">
				<div class="d-flex justify-content-end">
					<div class="btn-group" role="group">
						<button type="button" class="btn btn-success btn-remove">-</button>
						<button type="button" class="btn btn-success btn-add">+</button>
					</div>
				</div>
				<div class="d-flex gap-3 align-items-start flex-wrap">
					<div class="mb-3">
						<label class="form-label">Название</label>
						<input type="text"
							name="Subcategory[<?= $key ?>][title]"
							class="form-control"
							maxlength="255"
							value="<?= Html::encode($subcategory->title ?? '') ?>">
					</div>

					<div class="mb-3">
						<label class="form-label d-block">Расширяющая</label>
						<input type="hidden" name="Subcategory[<?= $key ?>][extend]" value="0">
						<input type="checkbox"
							name="Subcategory[<?= $key ?>][extend]"
							class="form-check-input fs-5 extend-checkbox"
							value="1"
							<?= !empty($subcategory->extend) ? 'checked' : '' ?>>
					</div>
					<input type="hidden" name="Subcategory[<?= $key ?>][id]" value="<?= $subcategory->id ?? '' ?>">
				</div>
			</div>
		<?php endforeach ?>
	</div>

	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php
$initialCount = count($subcategories);
$js = "var subcategoryCount = {$initialCount};";
$this->registerJs($js, \yii\web\View::POS_BEGIN);
$this->registerJsFile('@web/js/subcategory.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>