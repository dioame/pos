<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrArTracking */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="or-ar-tracking-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jev')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
