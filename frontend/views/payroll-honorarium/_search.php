<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollHonorariumSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payroll-honorarium-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'emp_id') ?>

    <?= $form->field($model, 'date_from') ?>

    <?= $form->field($model, 'date_to') ?>

    <?php // echo $form->field($model, 'number_of_hours') ?>

    <?php // echo $form->field($model, 'hourly_rate') ?>

    <?php // echo $form->field($model, 'date_created') ?>

    <?php // echo $form->field($model, 'dv') ?>

    <?php // echo $form->field($model, 'jev') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
