<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Comment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="comment-form">

	<?php $form = ActiveForm::begin([]); ?>

	<?= $form->field($model, 'text')->textarea(['rows' => 6])->label("Отзыв") ?>

	<div class="form-group d-flex justify-content-end">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-outline-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>