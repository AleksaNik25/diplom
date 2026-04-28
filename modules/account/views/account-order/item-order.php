<?php

use yii\bootstrap5\Html;

$firstImage = $model->product->productImages[0] ?? null;
?>
<div class="card mb-3">
	<div class="d-flex row">
		<div class="d-flex col-9">
			<div class="card" style="max-width: 10rem; min-width: 10rem;">
				<div class="img-cart-style">
					<?php if ($firstImage): ?>
						<img src="/img/<?= $firstImage->photo ?>"
							alt="<?= Html::encode($model->product->title) ?>"
							style="max-width: 10rem; min-height: 150px; max-height: 150px;"
							class="img-cart-style">
					<?php else: ?>
						<!-- Можно вывести заглушку, если нет изображений -->
						<img src="/img/no-image.jpg"
							alt="Нет изображения"
							style="max-width: 10rem; min-height: 150px; max-height: 150px;"
							class="img-cart-style">
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="d-flex gap-5 col-2 ps-3 fw-semibold">
			<div><?= $model->amount ?></div>
			<div><?= $model->cost ?></div>
			<div><?= $model->sum ?></div>
		</div>

	</div>
</div>