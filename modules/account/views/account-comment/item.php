<?php

use kartik\rating\StarRating;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$firstImage = $model->product->productImages[0] ?? null;
$viewUrl = Url::to(['/catalog/view', 'id' => $model->product->id]);
?>
<div class="card border-light-subtle mb-3">
	<div class="card-header d-flex justify-content-between flex-nowrap heder-cart-bg">
		<div class="d-flex gap-3 align-items-center flex-nowrap">
			<span class="text-nowrap text-light">
				<?= Yii::$app->formatter->asDatetime($model->created_at, "php:d.m.Y H:i:s") ?>
			</span>
			<?php if ($model->updated_at): ?>
				<span class="text-nowrap  text-light small">
					(изменен: <?= Yii::$app->formatter->asDatetime($model->updated_at, "php:d.m.Y H:i:s") ?>)
				</span>
			<?php endif ?>
			<?php
			$userRating = \app\models\Rating::find()
				->where(['user_id' => $model->user_id, 'product_id' => $model->product_id])
				->select('estimation')
				->scalar();
			?>
			<?php if ($userRating): ?>
				<?= StarRating::widget([
					'bsVersion' => '5.x',
					'name' => 'comment-rating-' . $model->id,
					'value' => $userRating,
					'pluginOptions' => [
						'size'        => 'xs',
						'readonly'    => true,
						'showClear'   => false,
						'showCaption' => false,
						'displayOnly' => true,
					],
				]) ?>
			<?php endif ?>
		</div>


		<div class="d-flex gap-3">
			<?= Html::a('<i class="fas fa-pencil-alt"></i>', ['edit', "id" => $model->id], ["class" => " text-light",  "data-pjax" => 0]) ?>
			<?= Html::a('<i class="far fa-trash-alt"></i>', ['delete', 'id' => $model->id], [
				"class" => " text-light",
				'data' => [
					'confirm' => 'Вы уверены что хотите удалить этот комментарий?',
					'method' => 'post',
				],
			]) ?>
		</div>
	</div>
	<div class="card-body d-flex justify-content-between">
		<?php if ($firstImage) {
			$img = Html::img(
				"/img/" . $firstImage->photo,
				[
					'alt' => Html::encode($model->product->title),
					'style' => "max-width: 10rem; min-height: 150px; max-height: 150px;",
					'class' => "img-cart-style"
				]
			);
		} else {
			$img = Html::img(
				"/img/no-image.jpg",
				[
					'alt' => "Нет изображения",
					'style' => "max-width: 10rem; min-height: 150px; max-height: 150px;",
					'class' => "img-cart-style"
				]
			);
		} ?>

		<?= Html::a(
			"<div class=\"card\" style=\"max-width: 10rem; min-width: 10rem;\">"
				. "<div class=\"img-cart-style\">"
				. $img
				. "</div></div>",
			$viewUrl,
			['data-pjax' => 0]
		); ?>

		<div class="card-body d-flex justify-content-start">
			<div class="m-2">
				<?= Html::a("<h5 class=\"card-title\"> " . $model->product->title . " </h5>", $viewUrl, ['class' => "text-decoration-none text-dark", 'data-pjax' => 0]) ?>
				<p class="card-text"><?= nl2br($model->text) ?></p>
			</div>
		</div>
		<div class="d-flex justify-content-end align-items-end">
			<?= Html::a('Подробности о товаре', $viewUrl, ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>
		</div>
	</div>


</div>