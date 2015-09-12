<?php
/**
 * Created by PhpStorm.
 * User: ildar
 * Date: 12.09.15
 * Time: 17:02
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Create news';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
	<h1><?= Html::encode($this->title) ?></h1>

	<p>Please fill out the following fields to add news:</p>

	<?php $form = ActiveForm::begin([
		'id' => 'register-form',
		'options' => ['class' => 'form-horizontal'],
		'fieldConfig' => [
			'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
			'labelOptions' => ['class' => 'col-lg-2 control-label'],
		],
	]); ?>

	<?= $form->field($model, 'title') ?>
	<?= $form->field($model, 'photo')->fileInput() ?>
	<?= $form->field($model, 'news')->textarea() ?>

	<div class="form-group">
		<div class="col-lg-offset-2 col-lg-11">
			<?= Html::submitButton('Add', ['class' => 'btn btn-primary', 'name' => 'add-button']) ?>
		</div>
	</div>

	<?php ActiveForm::end(); ?>

</div>
