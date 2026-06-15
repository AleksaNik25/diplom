<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */

$this->title = "Мои отзывы";
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// JS для модального окна редактирования
$this->registerJs(
    <<<JS
$(document).on('click', '.btn-comment-edit-review', function(e) {
    e.preventDefault();
    var id = $(this).data('id');
    var modal = $('#modal-edit-review');
    modal.modal('show');
    // Получаем текущий текст комментария
    $.getJSON('/account/account-comment/edit?id=' + id, function(data) {
        if (data && data.text !== undefined) {
            modal.find('textarea[name="Comment[text]"]').val(data.text);
            modal.find('#edit-comment-id').val(data.id);
        }
    });
});

$('#modal-edit-review').on('click', '#btn-edit-save', function(e) {
    e.preventDefault();
    var id = $('#modal-edit-review #edit-comment-id').val();
    var text = $('#modal-edit-review textarea[name="Comment[text]"]').val();
    $.ajax({
        url: '/account/account-comment/edit?id=' + id,
        method: 'POST',
        data: {
            'Comment[text]': text,
            '_csrf': yii.getCsrfToken()
        },
        success: function(data) {
            if (data.success) {
                $('#modal-edit-review').modal('hide');
                $.pjax.reload('#product-comments-pjax', {timeout: 5000});
            } else {
                alert('Ошибка при сохранении.');
            }
        }
    });
});
JS
);
?>
<div class="comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-3 d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['account-order/index'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <?php Pjax::begin([
        'id' => 'product-comments-pjax',
        'enablePushState' => false,
        'timeout' => 5000
    ]); ?>

    <?php if ($dataProvider->totalCount): ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemOptions' => ['class' => 'item'],
            'layout' => '{pager}<div class="d-flex flex-column gap-3">{items}</div>{pager}',
            'itemView' => 'item',
            'pager' => [
                'class' => LinkPager::class,
            ]
        ]) ?>

    <?php else: ?>
        <div class="alert alert-primary" role="alert">
            Вы еще не оставляли комментариев
        </div>
    <?php endif ?>

    <?php Pjax::end(); ?>

</div>

<!-- Модальное окно редактирования отзыва -->
<div class="modal fade" id="modal-edit-review" tabindex="-1" aria-labelledby="modalEditReviewLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditReviewLabel">Редактировать отзыв</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit-comment-id" value="">
                <div class="mb-3">
                    <label class="form-label">Текст отзыва</label>
                    <textarea name="Comment[text]" class="form-control" rows="5"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" id="btn-edit-save">Сохранить</button>
            </div>
        </div>
    </div>
</div>