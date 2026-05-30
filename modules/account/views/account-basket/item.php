<?php

use yii\bootstrap5\Html;
use yii\helpers\Url;

$firstImage = $model->product->productImages[0] ?? null;
$viewUrl = Url::to(['/catalog/view', 'id' => $model->product->id]);

// var_dump($model->product->productImages);
Yii::debug($model->product->productImages);
// Yii::debug($model->product?->productImages[0]);
Yii::debug($firstImage);
?>

<div class="order-card">
	<div class="order-card-body">

		<!-- Блок с информацией о товаре -->
		<div class="order-item d-flex justify-content-between align-items-center flex-wrap gap-3">
			<div class="order-item-image">
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
			</div>

			<!-- Название товара-->
			<div class="order-item-details text-center flex-fill">
				<?= Html::a(
					Html::encode($model->product->title),
					['/catalog/view', "id" => $model->product->id],
					['class' => 'text-decoration-none order-title d-block']
				) ?>

				<?php
				$mainCat = null;
				foreach ($model->product->categories as $cat) {
					if (!$cat->extend) {
						$mainCat = $cat;
						break;
					}
				}
				?>
				<span class="card-text text-secondary"><?= $mainCat ? Html::encode($mainCat->title) : '—' ?></span>
			</div>

			<!-- Цена за единицу + кнопки +/- и удалить -->
			<div class="order-item-price fs-5 fw-bold">
				<?= Yii::$app->formatter->asDecimal($model->price, 2) ?> ₽
			</div>

			<div class="d-flex gap-2 align-item-center">
				<div><?= Html::a('-', ['dec', 'item_id' => $model->id], ['class' => 'text-decoration-none px-2 basket-btn btn btn-primary', 'data-pjax' => 0]) ?></div>
				<div><span class="fs-5 px-2 text-dark"><?= $model->amount ?></span></div>
				<div><?= Html::a('+', ['add', 'product_id' => $model->product_id], ['class' => 'text-decoration-none px-2 basket-btn btn btn-primary', 'data-pjax' => 0]) ?>
				</div>
				<div><?= Html::a('<i class="far fa-trash-alt"></i>', ['delete', 'item_id' => $model->id], ['data-pjax' => 0, 'class' => 'text-decoration-none basket-btn text-dark px-2 fs-4']) ?></div>
			</div>
		</div>

		<!-- Блок с количеством и общей суммой ПОСЛЕ разделителя -->
		<div class="order-summary d-flex justify-content-between mt-3 pt-3 border-top">
			<div class="order-total fw-bold fs-5">
				Количество:
				<span class="fw-bold ms-1"><?= $model->amount ?></span>
				<span class="ms-1">шт.</span>
			</div>
			<div class="order-total fw-bold fs-4">
				<?= Yii::$app->formatter->asDecimal($model->sum, 2) ?> ₽
			</div>
		</div>

	</div>
</div>