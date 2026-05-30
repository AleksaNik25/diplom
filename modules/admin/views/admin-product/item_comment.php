<?php

use kartik\rating\StarRating;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\web\JqueryAsset;

?>
<div class="card border-light-subtle mb-3 ">
	<div class="card-header d-sm-flex justify-content-between flex-nowrap heder-cart-bg">
		<div class="d-sm-flex gap-3 align-items-center flex-nowrap">
			<span class="text-nowrap text-light">
				<?= Yii::$app->formatter->asDatetime($model->created_at, "php:d.m.Y H:i:s") ?>
			</span>

			<?php if ($model->updated_at): ?>
				<span class="text-nowrap text-light small">
					(изменен: <?= Yii::$app->formatter->asDatetime($model->updated_at, "php:d.m.Y H:i:s") ?>)
				</span>
			<?php endif ?>

			<?php if ($model->isSellerComment): ?>
				<span class="badge bg-success">Продавец товара</span>
			<?php endif ?>

			<?php if (!$model->parent_id): ?>
				<?php
				$userRating = \app\models\Rating::find()
					->where(['user_id' => $model->user_id, 'product_id' => $model->product_id])
					->select('estimation')->scalar();
				?>
				<?php if ($userRating): ?>
					<?= StarRating::widget([
						'bsVersion' => '5.x',
						'name' => 'comment-rating-' . $model->id,
						'value' => $userRating,
						'pluginOptions' => [
							'size' => 'xs',
							'readonly' => true,
							'showClear' => false,
							'showCaption' => false,
							'displayOnly' => true
						],
					]) ?>
				<?php endif ?>
			<?php endif ?>
		</div>

		<!-- Кнопки редактирования/удаления -->
		<div class="d-flex gap-3">
			<?= $model->user_id === Yii::$app->user->id
				? Html::a('<i class="fas fa-pencil-alt"></i>', ['/account/account-comment/write', "product_id" => $model->product_id], ["class" => "text-light btn-comment-edit",  "data-pjax" => 0,])
				: '' ?>
			<?= $model->user_id === Yii::$app->user->id || Yii::$app->user->identity?->isAdmin
				? Html::a('<i class="far fa-trash-alt"></i>', ['/account/account-comment/delete', 'id' => $model->id], [
					"class" => "text-light",
					'data' => [
						'confirm' => 'Вы уверены что хотите удалить этот комментарий?',
						'method' => 'post',
					],
				])
				: '' ?>
		</div>
	</div>
	<div class="card-body">
		<h5 class="card-title"><?= $model->user->login ?></h5>
		<p class="card-text"><?= nl2br($model->text) ?></p>

		<?php if ($model->canReply()): ?>
			<div class="mt-2">
				<?= Html::button(
					'<i class="fas fa-reply me-1"></i>Ответить',
					[
						'class' => 'btn btn-sm btn-outline-primary',
						'data-bs-toggle' => 'modal',
						'data-bs-target' => '#modal-comment',
						'data-product-id' => $model->product_id,
						'data-parent-id' => $model->id,
					]
				) ?>
			</div>
		<?php endif; ?>
	</div>

	<!-- Блок ответов  -->
	<?php if (!$model->parent_id && $model->replies): ?>
		<div class="card-footer bg-light-subtle pt-3">
			<?php foreach ($model->replies as $reply): ?>
				<?= $this->render('_reply_thread', ['reply' => $reply, 'depth' => 0]) ?>
			<?php endforeach ?>
		</div>
	<?php endif; ?>
</div>


<?php
$this->registerJsFile("/js/comment.js", ['depends' => JqueryAsset::class]);
?>