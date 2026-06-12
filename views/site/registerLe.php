<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Регистрация продавца';
?>

<div class="container d-flex justify-content-center">
    <div class="site-contact register-login-style">
        <h1 class="text-center pt-4"><?= Html::encode($this->title) ?></h1>

        <div class="row d-flex justify-content-center p-4">
            <div class="col-lg-6">

                <?php $form = ActiveForm::begin([
                    'id' => 'contact-form',
                    'options' => ['enctype' => 'multipart/form-data'],
                ]); ?>

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

                <?= $form->field($model, 'inn')->textInput(['placeholder' => '123456789123']) ?>

                <?= $form->field($model, 'snils')->textInput(['placeholder' => '123-456-789 10']) ?>

                <?= $form->field($model, 'shop_title')->textInput(['placeholder' => 'FlowerStor']) ?>

                <?= $form->field($model, 'login')->textInput(['placeholder' => 'IvanovIvan']) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

                <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

                <div class="form-group pt-3 text-center">
                    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
<?php
$this->registerJsFile('js/register.js', ['depends' => 'yii\web\YiiAsset']);
