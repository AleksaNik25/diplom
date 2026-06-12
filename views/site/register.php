<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Регистрация';
?>

<div class="container d-flex justify-content-center">
    <div class="site-contact register-login-style">
        <h1 class="text-center pt-4"><?= Html::encode($this->title) ?></h1>

        <div class="row d-flex justify-content-center p-4">
            <div class="col-lg-6">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'surname')->textInput(['autofocus' => true, 'placeholder' => 'Иванов']) ?>

                <?= $form->field($model, 'name')->textInput(['placeholder' => 'Иван']) ?>

                <?= $form->field($model, 'patronymic')->textInput(['placeholder' => 'Иванович']) ?>

                <?= $form->field($model, 'email')->textInput(['placeholder' => 'ivanov@mail.ru']) ?>

                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::class, [
                    'mask' => '+7(999)-999-99-99',
                    'options' => [
                        'placeholder' => '+7(999)-999-99-99',
                    ],
                ])  ?>

                <?= $form->field($model, 'login')->textInput(['placeholder' => 'IvanovIvan']) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

                <div class="form-group pt-3 text-center">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
                </div>

                <div class="form-group pt-3 text-center">
                    Уже есть аккаунт? <?= Html::a('Войти', ['site/login'], ['class' => 'text-success text-decoration-none']) ?>
                </div>

                <div class="pt-3 pb-3 text-center">
                    <?= Html::a('Зарегистрироваться как продавец', ['site/register-le'], ['class' => 'text-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>