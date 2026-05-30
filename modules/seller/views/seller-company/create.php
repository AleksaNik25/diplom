<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Company $model */

$this->title = 'Добавить компанию';
$this->params['breadcrumbs'][] = ['label' => 'Мои компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('create-company', [
        'modelInfo' => $model,
        'company' => $company,
    ]) ?>

</div>