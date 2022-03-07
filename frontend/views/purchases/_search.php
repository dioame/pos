<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PurchasesDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="purchases-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'payee') ?>

    <?= $form->field($model, 'date_posted') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'amount_paid') ?>

    <?php // echo $form->field($model, 'accounts_payable') ?>

    <?php // echo $form->field($model, 'dv_number') ?>

    <?php // echo $form->field($model, 'ap_invoice') ?>

    <?php // echo $form->field($model, 'jev') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
