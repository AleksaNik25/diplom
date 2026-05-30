<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Company $model */

$this->title = 'Редактирование компании: ' . $model->companyInfo->title;
$this->params['breadcrumbs'][] = ['label' => 'Мои компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->companyInfo->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('create-company', [
        'model' => $model,
        'modelInfo' => $modelInfo,
        'company' => $model,
    ]) ?>

</div>
