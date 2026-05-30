<?php

use yii\bootstrap5\Html;
?>
<div class="card border-light-subtle mb-3 heder-cart-bg">
	<div class="card-header d-flex justify-content-between">
		<div class="d-flex gap-3">
			<?= Yii::$app->formatter->asDatetime($model->created_at, "php:d.m.Y H:i:s") ?>
			<?php if ($model->updated_at): ?>
				(отредактирован: <?= Yii::$app->formatter->asDatetime($model->updated_at, "php:d.m.Y H:i:s") ?>)
			<?php endif ?>
		</div>


		<div class="d-flex gap-3">

			<?= Html::a('<i class="fas fa-pencil-alt"></i>', ['edit', "id" => $model->id], ["class" => "text-light",  "data-pjax" => 0]) ?>
			<?= Html::a('<i class="far fa-trash-alt"></i>', ['delete', 'id' => $model->id], [
				"class" => "text-light",
				'data' => [
					'confirm' => 'Вы уверены что хотите удалить этот комментарий?',
					'method' => 'post',
				],
			]) ?>

		</div>
	</div>
	<div class="card-body">
		<h5 class="card-title"><?= $model->user->login ?></h5>
		<p class="card-text"><?= nl2br($model->text) ?></p>
	</div>

</div>