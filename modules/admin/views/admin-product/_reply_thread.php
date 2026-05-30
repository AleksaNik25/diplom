<?php

/**
 * @var app\models\Comment $reply
 * @var int $depth
 */

use yii\bootstrap5\Html;

$indent = min($depth, 3) * 16;
$currentUser = Yii::$app->user->identity;

// Уже ответил ли текущий пользователь на этот комментарий
$alreadyReplied = $reply->replies && array_filter(
	$reply->replies,
	fn($r) => $r->user_id === Yii::$app->user->id
);
?>
<div class="reply-item mb-2 border-start border-3 <?= $reply->isSellerComment ? 'border-success' : 'border-info' ?>"
	style="padding-left: <?= 8 + $indent ?>px;">

	<div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
		<div class="d-flex align-items-center gap-2">
			<strong class="small"><?= Html::encode($reply->user->login) ?></strong>
			<?php if ($reply->isSellerComment): ?>
				<span class="badge bg-success" style="font-size: 0.6rem">Продавец товара</span>
			<?php endif ?>
			<small class="text-muted" style="font-size: 0.75rem">
				<?= Yii::$app->formatter->asDatetime($reply->created_at, "php:d.m.Y H:i:s") ?>
			</small>
			<?php if ($reply->updated_at): ?>
				<small class="text-muted" style="font-size: 0.7rem">
					(изменён: <?= Yii::$app->formatter->asDatetime($reply->updated_at, "php:d.m.Y H:i:s") ?>)
				</small>
			<?php endif ?>
		</div>

		<!-- Кнопки управления ответом -->
		<div class="d-flex gap-2">
			<?php if ($currentUser && $reply->user_id === $currentUser->id): ?>
				<?= Html::button(
					'<i class="fas fa-pencil-alt"></i>',
					[
						'class' => 'text-dark btn-reply-edit',
						'style' => 'background:none;border:none;padding:0;cursor:pointer;',
						'data-reply-id' => $reply->id,
						'data-product-id' => $reply->product_id,
						'data-bs-toggle' => 'modal',
						'data-bs-target' => '#modal-comment',
						'data-parent-id' => $reply->parent_id,
						'data-edit-id' => $reply->id,
						'title' => 'Редактировать',
					]
				) ?>
			<?php endif ?>
			<?php if ($currentUser && ($reply->user_id === $currentUser->id || $currentUser->isAdmin)): ?>
				<?= Html::a(
					'<i class="far fa-trash-alt"></i>',
					['/account/account-comment/delete', 'id' => $reply->id],
					[
						'class' => 'text-dark',
						'style' => 'text-decoration:none;',
						'data' => [
							'confirm' => 'Вы уверены что хотите удалить этот ответ?',
							'method' => 'post',
						],
					]
				) ?>
			<?php endif ?>
		</div>
	</div>

	<p class="mb-1 small mt-1"><?= nl2br(Html::encode($reply->text)) ?></p>

	<?php if (!$alreadyReplied && $reply->canReply()): ?>
		<div class="mt-1">
			<?= Html::button(
				'<i class="fas fa-reply me-1"></i>Ответить',
				[
					'class' => 'btn btn-sm btn-outline-primary btn-reply',
					'data-bs-toggle' => 'modal',
					'data-bs-target' => '#modal-comment',
					'data-product-id' => $reply->product_id,
					'data-parent-id' => $reply->id,
				]
			) ?>
		</div>
	<?php endif ?>

	<?php if ($reply->replies): ?>
		<div class="mt-2">
			<?php foreach ($reply->replies as $child): ?>
				<?= $this->render('_reply_thread', [
					'reply' => $child,
					'depth' => $depth + 1,
				]) ?>
			<?php endforeach ?>
		</div>
	<?php endif ?>
</div>