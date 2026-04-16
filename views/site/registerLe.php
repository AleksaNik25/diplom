<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container d-flex justify-content-center">
    <div class="site-contact register-login-style">
        <h1 class="text-center pt-4"><?= Html::encode($this->title) ?></h1>

        <div class="row d-flex justify-content-center pt-4 pb-3">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'surname')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'name') ?>

                <?= $form->field($model, 'patronymic') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'phone') ?>

                <?= $form->field($model, 'inn') ?>

                <?= $form->field($model, 'snils') ?>

                <?= $form->field($model, 'shop_title') ?>

                <?= $form->field($model, 'login') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <!-- <?php #$form->field($model, 'password')->passwordInput() ?> повтор пароля -->

                <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

                <div class="form-group pt-3 text-center">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>