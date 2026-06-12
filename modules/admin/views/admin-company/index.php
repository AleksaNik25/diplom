<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\AdminCompanySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Компании';
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['admin-order/index'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            [
                'label' => 'Наименование',
                'value' => fn($model) => $model->companyInfo->title,
            ],
            [
                'label' => 'Контактное лицо',
                'value' => fn($model) => $model->companyInfo->person,
            ],
            [
                'label' => 'ИНН',
                'value' => fn($model) => $model->companyInfo->inn,
            ],
            [
                'label' => 'Юридический адрес',
                'value' => fn($model) => $model->companyInfo->address,
            ],
            [
                'label' => 'Email',
                'value' => fn($model) => $model->companyInfo->email,
            ],
            [
                'label' => 'Статус',
                'value' => fn($model) => $model->approval == 1
                    ? 'Подтверждена'
                    : 'Ожидает подтверждения',
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view} {approve}',
                'buttons'  => [
                    'view' => fn($url, $model) => Html::a(
                        '<i class="fas fa-eye"></i>',
                        ['view', 'id' => $model->id],
                        ['class' => 'btn btn-sm btn-outline-success']
                    ),
                    'approve' => fn($url, $model) => $model->approval != 1
                        ? Html::a(
                            '<i class="fas fa-check"></i>',
                            ['approve', 'id' => $model->id],
                            [
                                'class' => 'btn btn-sm btn-outline-primary',
                                'data'  => [
                                    'confirm' => 'Подтвердить компанию?',
                                    'method'  => 'post',
                                ],
                            ]
                        )
                        : '',
                ],
            ],
        ],
    ]) ?>

    <?php Pjax::end(); ?>

</div>