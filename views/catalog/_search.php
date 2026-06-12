<?php

use app\models\Category;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\CatalogSearch $model */

// Корневые категории
$roots = Category::find()->where(['parent_id' => null, 'extend' => null])->all();

// Подкатегории сгруппированные по корневой
$subcatsByRoot = [];
$allSubs = Category::find()->where(['not', ['parent_id' => null]])->all();
foreach ($allSubs as $sub) {
    $subcatsByRoot[$sub->parent_id][] = $sub;
}

// Название выбранной категории для кнопки
$selectedLabel = 'Выберите категорию товара';
if ($model->category_id) {
    $found = Category::findOne($model->category_id);
    if ($found) $selectedLabel = $found->title;
}
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'd-flex flex-wrap align-items-end gap-3',
            'id' => 'form-search',
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['placeholder' => 'Название']) ?>

    <input type="hidden" name="CatalogSearch[category_id]"
        id="search-category-id"
        value="<?= Html::encode($model->category_id) ?>">

    <div class="mb-3 position-relative" id="category-dropdown-wrap">
        <button type="button"
            class=" border btn btn-light d-flex align-items-center gap-2"
            id="category-dropdown-btn"
            style="min-width: 240px; justify-content: space-between;">
            <span id="category-dropdown-label"><?= Html::encode($selectedLabel) ?></span>
            <i class="fas fa-chevron-down" id="category-chevron"></i>
        </button>

        <div id="category-popup"
            class="border rounded shadow-sm p-3 category-popup"
            style="display:none; position:absolute; top:100%; right:0; z-index:1050;
                    background:#ffffff; min-width: 900px; max-width: 1100px;">

            <div class="d-flex gap-4">
                <?php foreach ($roots as $root): ?>
                    <?php $subs = $subcatsByRoot[$root->id] ?? [] ?>
                    <div style="min-width: 250px; border-right: 1px solid #3A7F0C;">

                        <div class="fw-bold mb-2 category-option"
                            style="cursor:pointer;"
                            data-id="<?= $root->id ?>"
                            data-label="<?= Html::encode($root->title) ?>">
                            <?= Html::encode($root->title) ?>
                        </div>

                        <div class="row row-cols-2 g-1">
                            <?php foreach ($subs as $sub): ?>
                                <div class="col">
                                    <div class="category-option <?= $sub->extend ? 'text-muted fst-italic' : '' ?>"
                                        style="cursor:pointer; font-size: 0.875rem; padding: 2px 4px; border-radius: 4px;"
                                        data-id="<?= $sub->id ?>"
                                        data-label="<?= Html::encode($sub->title) ?>">
                                        <?= Html::encode($sub->title) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <div class="form-group mb-3 d-flex gap-2">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary', 'id' => 'search-submit-btn']) ?>
        <?= Html::a('<i class="fas fa-redo-alt"></i>', ['/catalog'], ['class' => 'btn btn-outline-primary', 'data-pjax' => 0]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>