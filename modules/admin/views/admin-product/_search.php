<?php

use app\models\Category;
use app\models\Status;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\AdminProductSearch $model */

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

// Статусы 1-3
$statuses = Status::find()->where(['<=', 'id', 3])->orderBy('id')->all();
$statusOptions = ['' => 'Все статусы'];
foreach ($statuses as $s) {
    $statusOptions[$s->id] = $s->title;
}
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'd-flex align-items-end gap-3 flex-wrap',
            'id' => 'form-search',
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['placeholder' => 'Название'])->label('Название') ?>

    <?= $form->field($model, 'status_id')->dropDownList($statusOptions, ['class' => 'form-select'])->label('Статус') ?>

    <input type="hidden" name="AdminProductSearch[category_id]"
        id="search-category-id"
        value="<?= Html::encode($model->category_id) ?>">

    <!-- Кастомный дропдаун категорий -->
    <div class="mb-3 position-relative" id="category-dropdown-wrap">
        <label class="form-label">Категория</label>
        <button type="button"
            class="border btn btn-light d-flex align-items-center gap-2"
            id="category-dropdown-btn"
            style="min-width: 240px; justify-content: space-between;">
            <span id="category-dropdown-label"><?= Html::encode($selectedLabel) ?></span>
            <i class="fas fa-chevron-down" id="category-chevron"></i>
        </button>

        <div id="category-popup"
            class="border rounded shadow-sm p-3 category-popup"
            style="display:none; position:absolute; top:100%; z-index:1050;
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
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-redo-alt"></i>', ['index'], ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>