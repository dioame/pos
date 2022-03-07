<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PcvTrackingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pcv-tracking-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'dv_number') ?>

    <?= $form->field($model, 'date_posted') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'payee') ?>

    <?php // echo $form->field($model, 'particular') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'debit') ?>

    <?php // echo $form->field($model, 'credit') ?>

    <?php // echo $form->field($model, 'prepared_by') ?>

    <?php // echo $form->field($model, 'requested_by') ?>

    <?php // echo $form->field($model, 'approved_by') ?>

    <?php // echo $form->field($model, 'received_by') ?>

    <?php // echo $form->field($model, 'jev') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
