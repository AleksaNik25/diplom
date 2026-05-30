<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Category $model */
/** @var app\models\Category[] $subcategories */

$this->title = 'Создание подкатегории';
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create-sub">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form_sub', [
		'model'         => $model,
		'subcategories' => $subcategories,
	]) ?>

</div>