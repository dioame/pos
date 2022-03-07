<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExpensesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expenses-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'date') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'unit_cost') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'amount_paid') ?>

    <?php // echo $form->field($model, 'balance') ?>

    <?php // echo $form->field($model, 'supplier') ?>

    <?php // echo $form->field($model, 'jev') ?>

    <?php // echo $form->field($model, 'dv') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
