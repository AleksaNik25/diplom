<?php

use app\models\Category;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$rootList = ArrayHelper::map(
    Category::find()->where(['parent_id' => null, 'extend' => null])->all(),
    'id',
    'title'
);

$subcatsByRoot = [];
$allSubs = Category::find()->where(['not', ['parent_id' => null]])->all();
foreach ($allSubs as $sub) {
    $subcatsByRoot[$sub->parent_id][] = ['id' => $sub->id, 'title' => $sub->title, 'extend' => $sub->extend];
}
$subcatsByRootJson = json_encode($subcatsByRoot);

$selectedIds = array_map(fn($c) => $c->id, $model->categories ?? []);

$currentRootId = null;
if (!empty($selectedIds)) {
    $firstCat = Category::findOne($selectedIds[0]);
    $currentRootId = $firstCat ? $firstCat->parent_id : null;
}
?>

<p>
    <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], ['class' => 'btn btn-outline-primary']) ?>
</p>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'], 'id' => 'form-product']); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'preview')->textarea(['rows' => 3]) ?>
    <?= $form->field($model, 'care_recommendations')->textarea(['rows' => 6]) ?>

    <div class="mb-3">
        <label class="form-label">Выберите категорию товара</label>
        <select id="root-category-select" class="form-select col-6" style="max-width: 400px">
            <option value="">Выберите категорию</option>
            <?php foreach ($rootList as $id => $title): ?>
                <option value="<?= $id ?>" <?= $currentRootId == $id ? 'selected' : '' ?>>
                    <?= Html::encode($title) ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>

    <div id="subcategory-block" class="mb-3" style="<?= $currentRootId ? '' : 'display:none' ?>">
        <div class="border rounded p-3" style="background: #f8f8f8">
            <p class="mb-3">Подкатегория</p>
            <div id="subcategory-grid" class="row row-cols-3 g-2">
                <?php if ($currentRootId && isset($subcatsByRoot[$currentRootId])): ?>
                    <?php foreach ($subcatsByRoot[$currentRootId] as $sub): ?>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                    name="category_ids[]"
                                    id="subcat-<?= $sub['id'] ?>"
                                    value="<?= $sub['id'] ?>"
                                    <?= in_array($sub['id'], $selectedIds) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="subcat-<?= $sub['id'] ?>">
                                    <?= Html::encode($sub['title']) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->isNewRecord && $model->productImages): ?>
        <div class="mb-2">
            <label class="form-label">Текущие фото:</label>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($model->productImages as $img): ?>
                    <img src="/img/<?= $img->photo ?>"
                        style="height: 80px; width: 80px; object-fit: cover; border-radius: 6px;">
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>

    <?= $form->field($model, 'imageFiles[]')
        ->fileInput(['multiple' => true, 'accept' => 'image/*'])
        ->hint($model->isNewRecord ? '' : 'Оставьте пустым, чтобы сохранить текущие фото. При загрузке новых — заменят существующие.')
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJs("var subcatsByRoot = {$subcatsByRootJson};", \yii\web\View::POS_BEGIN);
$this->registerJsFile("/js/product.js", ["depends" => JqueryAsset::class]);
?>