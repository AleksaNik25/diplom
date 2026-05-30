<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Продавец', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>

        <?php if ($model->userLE): ?>
            <?php if ($model->userLE->approval == 0): ?>
                <?= Html::a('Утвердить', ['change-status', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Заблокировать', ['change-status', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
            <?php elseif ($model->userLE->approval == 1): ?>
                <?= Html::a('Заблокировать', ['change-status', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
            <?php elseif ($model->userLE->approval == 2): ?>
                <span class="badge bg-danger align-self-center">Заблокирован</span>
                <?= Html::a('Разблокировать', ['change-status', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'name',
                'value' => $model->name,
            ],
            [
                'attribute' => 'surname',
                'value' => $model->surname,
            ],
            [
                'attribute' => 'patronymic',
                'value' => $model->patronymic,
            ],
            [
                'attribute' => 'login',
                'value' => $model->login,
            ],
            [
                'attribute' => 'email',
                'value' => $model->email,
            ],
            [
                'attribute' => 'phone',
                'value' => $model->phone,
            ],
            [
                'attribute' => 'shop_title',
                'label' => 'Название магазина',
                'value' => $model->userLE->shop_title,
            ],
            [
                'attribute' => 'inn',
                'label' => 'ИНН',
                'value' => $model->userLE->inn,
            ],
            [
                'attribute' => 'snils',
                'label' => 'СНИЛС',
                'value' => $model->userLE->snils,
            ],
            [
                'attribute' => 'approval',
                'label' => 'Статус',
                'value' => $model->userLE->approval == 1 ? 'Подтвержден' : 'Ожидает подтверждения',
            ],
        ],
    ]) ?>

    <?php if ($model->userLE && $model->userLE->userDocs): ?>
        <h4 class="my-3">Документы продавца</h4>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <?php foreach ($model->userLE->userDocs as $doc): ?>
                <?php $ext = strtolower(pathinfo($doc->photo, PATHINFO_EXTENSION)); ?>
                <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'avif'])): ?>
                    <img src="/doc/<?= $doc->photo ?>"
                        style="height: 150px; width: 150px; object-fit: cover; border-radius: 6px; cursor: pointer;"
                        onclick="window.open(this.src)"
                        title="Нажмите для просмотра">
                <?php else: ?>
                    <a href="/doc/<?= $doc->photo ?>" class="btn btn-outline-primary" target="_blank">
                        <?= $doc->photo ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php elseif ($model->userLE): ?>
        <p class="mt-3 text-muted">Документы не прикреплены</p>
    <?php endif; ?>

</div>