<?php

use app\models\Status;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\modules\account\models\AccountOrderSearch $model */
/** @var yii\widgets\ActiveForm $form */

$statuses = Status::find()->where(['between', 'id', 6, 9])->orderBy('id')->all();
$statusOptions = ['' => 'Все статусы'];
foreach ($statuses as $s) {
    $statusOptions[$s->id] = $s->title;
}
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
            'class' => 'd-flex align-items-end gap-3',
            'id' => 'form-search'
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status_id')->dropDownList($statusOptions, ['class' => 'form-select'])->label('Статус') ?>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fas fa-redo-alt"></i>', 'index', ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>