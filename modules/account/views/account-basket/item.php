<?php

use yii\bootstrap5\Html;

$firstImage = $model->product->productImages[0] ?? null;

// var_dump($model->product->productImages);
Yii::debug($model->product->productImages);
// Yii::debug($model->product?->productImages[0]);
Yii::debug($firstImage);
?>

<div class="card mb-3">
	<div class="card-body d-flex justify-content-between">
		<div class="d-flex">
			<div class="card" style="max-width: 18rem; min-width: 18rem;">
				<div class="img-cart-style">
					<?php if ($firstImage): ?>
						<img src="/img/<?= $firstImage->photo ?>"
							alt="<?= Html::encode($model->product->title) ?>"
							style="max-width: 18rem; min-height: 250px; max-height: 250px;"
							class="img-cart-style">
					<?php else: ?>
						<!-- Можно вывести заглушку, если нет изображений -->
						<img src="/img/no-image.jpg"
							alt="Нет изображения"
							style="max-width: 18rem; min-height: 250px; max-height: 250px;"
							class="img-cart-style">
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="d-flex justify-content-start">
			<h5 class="ml-3"><?= Html::a($model->product->title, ["/catalog/view", "id" => $model->product->id]) ?></h5>
		</div>
		<div class="d-flex gap-3">
			<div><?= Html::a('-', ['dec', 'item_id' => $model->id], ['class' => 'text-decoration-none basket-btn', 'data-pjax' => 0]) ?></div>
			<div><?= $model->amount ?></div>
			<div><?= Html::a('+', ['add', 'product_id' => $model->product_id], ['class' => 'text-decoration-none  basket-btn', 'data-pjax' => 0]) ?></div>
			<div><?= $model->price ?></div>
			<div><?= $model->sum ?></div>
			<div><?= Html::a('🗑', ['delete', 'item_id' => $model->id], ['data-pjax' => 0, 'class' => 'text-decoration-none  basket-btn', 'data-method' => 'post']) ?></div>
		</div>

	</div>
</div>