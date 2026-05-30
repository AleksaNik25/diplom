<?php

use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Order $model */

$this->title = 'Редакстирование отзыва: №' . $model->id . " от " . Yii::$app->formatter->asDatetime($model->created_at, "php:d.m.Y H:i:s");
$this->params['breadcrumbs'][] = ['label' => 'Отзывы', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Редактирование отзыва';
?>
<div class="order-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form_edit', [
        'model' => $model,
    ]) ?>

</div>