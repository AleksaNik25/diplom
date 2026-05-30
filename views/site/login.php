<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';
?>

<div class="container d-flex justify-content-center">
    <div class="site-login register-login-style">
        <h1 class="text-center pt-4"><?= Html::encode($this->title) ?></h1>

        <div class="row d-flex justify-content-center pt-2 pb-3">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                        'inputOptions' => ['class' => 'col-lg-3 form-control'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>

                <?= $form->field($model, 'login')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <!-- <?= $form->field($model, 'rememberMe')->checkbox([
                            'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        ]) ?> -->

                <div class="form-group pt-2 text-center">
                    <div>
                        <?= Html::submitButton('Войти', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

                <div style="color:#999;">
                    Админ: <strong>admin/admin</strong> <br> Пользователь: <strong>ivan/ivan</strong> или <strong>alex/alex</strong> <br> Продавец: <strong>q/q</strong> или <strong>a/a</strong>
                </div>

            </div>
        </div>
    </div>
</div>