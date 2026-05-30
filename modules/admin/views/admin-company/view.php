<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Company $model */

$this->title = $model->companyInfo->title;
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="d-flex gap-2">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index', 'id' => $model->id], ['class' => 'btn btn-outline-primary']) ?>
        <?= $model->approval != 1
            ? Html::a('Подтвердить', ['approve', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Подтвердить компанию?',
                    'method' => 'post',
                ],
            ])
            : ''
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                "label" => 'Наименование компании',
                "value" => $model->companyInfo->title,
            ],
            [
                "label" => 'Контактное лицо',
                "value" => $model->companyInfo->person,
            ],
            [
                "label" => 'ИНН',
                "value" => $model->companyInfo->inn,
            ],
            [
                "label" => 'Юридический адрес',
                "value" => $model->companyInfo->address,
            ],
            [
                "label" => 'Адрес электронной почты',
                "value" => $model->companyInfo->email,
            ],
            [
                "label" => 'Статус',
                "value" => $model->approval == 1 ? 'Подтверждена' : 'Ожидает подтверждения',
            ],
        ],
    ]) ?>

    <?php if ($model->companyDocs): ?>
        <h4 class="my-3">Документы компании</h4>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <?php foreach ($model->companyDocs as $doc): ?>
                <?php $ext = pathinfo($doc->photo, PATHINFO_EXTENSION); ?>
                <?php if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])): ?>
                    <img src="/company_docs/<?= $doc->photo ?>"
                        style="height: 120px; width: 120px; object-fit: cover; border-radius: 6px;">
                <?php else: ?>
                    <a href="/company_docs/<?= $doc->photo ?>"
                        class="btn btn-outline-primary" target="_blank">
                        <?= $doc->photo ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>