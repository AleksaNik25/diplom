<?php

use app\models\Category;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;

// Загружаем всю иерархию одним запросом
$allCategories = Category::find()->orderBy(['parent_id' => SORT_ASC, 'id' => SORT_ASC])->all();

$roots    = [];
$children = [];

foreach ($allCategories as $cat) {
    if ($cat->parent_id === null) {
        $roots[] = $cat;
    } else {
        $children[$cat->parent_id][] = $cat;
    }
}
?>

<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['admin-order/index'], ['class' => 'btn btn-outline-primary']) ?>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Создать подкатегорию', ['create-sub'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="accordion" id="categories-accordion">
        <?php foreach ($roots as $i => $root): ?>
            <?php $rootChildren = $children[$root->id] ?? [] ?>
            <div class="accordion-item mb-2 border rounded">

                <!-- Заголовок аккордеона -->
                <div class="accordion-header d-flex align-items-center px-3 py-2 gap-2">
                    <button class="accordion-button collapsed fw-bold fs-5 flex-grow-1 bg-transparent border-0 text-start p-0"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse-<?= $root->id ?>"
                        aria-expanded="false">
                        <?= Html::encode($root->title) ?>
                    </button>
                    <div class="d-flex gap-2 flex-shrink-0">
                        <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $root->id], ['class' => 'btn btn-sm btn-outline-success']) ?>
                        <?= Html::a('<i class="fas fa-pen"></i>', ['update', 'id' => $root->id], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                        <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $root->id], [
                            'class' => 'btn btn-sm btn-outline-danger',
                            'data'  => ['confirm' => 'Удалить категорию?', 'method' => 'post'],
                        ]) ?>
                        <i class="fas fa-chevron-down accordion-chevron text-secondary"></i>
                    </div>
                </div>

                <!-- Тело аккордеона -->
                <div id="collapse-<?= $root->id ?>" class="accordion-collapse collapse">
                    <div class="accordion-body p-3">
                        <?php if (empty($rootChildren)): ?>
                            <p class="text-muted mb-0">Подкатегорий нет</p>
                        <?php else: ?>
                            <?php
                            // Делим дочерние на два столбца
                            $half = (int) ceil(count($rootChildren) / 2);
                            $col1 = array_slice($rootChildren, 0, $half);
                            $col2 = array_slice($rootChildren, $half);
                            ?>
                            <div class="row g-3">
                                <?php foreach ([$col1, $col2] as $col): ?>
                                    <?php if (empty($col)) continue ?>
                                    <div class="col-6">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>№</th>
                                                    <th>Название</th>
                                                    <th>Расширение</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($col as $child): ?>
                                                    <tr>
                                                        <td class="text-muted"><?= $child->id ?></td>
                                                        <td><?= Html::encode($child->title) ?></td>
                                                        <td><?= $child->extend ? '<span>Да</span>' : '—' ?></td>
                                                        <td class="text-nowrap">
                                                            <?= Html::a('<i class="fas fa-eye"></i>', ['view', 'id' => $child->id], ['class' => 'btn btn-sm btn-outline-success me-1']) ?>
                                                            <?= Html::a('<i class="fas fa-pen"></i>', ['update', 'id' => $child->id], ['class' => 'btn btn-sm btn-outline-primary me-1']) ?>
                                                            <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $child->id], [
                                                                'class' => 'btn btn-sm btn-outline-danger',
                                                                'data'  => ['confirm' => 'Удалить подкатегорию?', 'method' => 'post'],
                                                            ]) ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

            </div>
        <?php endforeach ?>
    </div>

</div>

<?php
$css = <<<CSS
.accordion-item { overflow: hidden; }
.accordion-button::after { display: none; }
.accordion-chevron { transition: transform 0.2s ease; pointer-events: none; }
.accordion-button:not(.collapsed) ~ * .accordion-chevron,
.accordion-header:has(.accordion-button:not(.collapsed)) .accordion-chevron {
    transform: rotate(180deg);
}
CSS;
$this->registerCss($css);
?>