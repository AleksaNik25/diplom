<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\modules\admin\models\AdminUserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Работа с продавцами';
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p class="mt-3">
        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['admin-order/index'], ['class' => 'btn btn-outline-primary']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            [
                'label' => 'Логин',
                'value' => fn($model) => $model->login,
            ],
            [
                'label' => 'Email',
                'value' => fn($model) => $model->email,
            ],
            [
                'label' => 'Телефон',
                'value' => fn($model) => $model->phone,
            ],
            [
                'label' => 'ИНН',
                'value' => fn($model) => $model->userLE->inn,
            ],
            [
                'label' => 'СНИЛС',
                'value' => fn($model) => $model->userLE->snils,
            ],
            [
                'label' => 'Название магазина',
                'value' => fn($model) => $model->userLE->shop_title,
            ],
            [
                'label' => 'Статус',
                'value' => fn($model) => match ($model->userLE->approval) {
                    1 => 'Подтверждён',
                    2 => 'Заблокирован',
                    default => 'Ожидает подтверждения',
                },
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons'  => [
                    'view' => fn($url, $model) => Html::a(
                        '<i class="fas fa-eye"></i>',
                        ['view', 'id' => $model->id],
                        ['class' => 'btn btn-sm btn-outline-success']
                    ),
                ],
            ],
        ],
    ]) ?>

    <?php Pjax::end(); ?>

</div>