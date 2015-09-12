<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ConfirmForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Confirmation';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill auth key and password for complete registration:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'register-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'authKey') ?>
        <?= $form->field($model, 'password') ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('Confirm', ['class' => 'btn btn-primary', 'name' => 'confirm-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
