<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PpeDepreciationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppe-depreciation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'ppeID') ?>

    <?= $form->field($model, 'date_from') ?>

    <?= $form->field($model, 'date_to') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'date_posted') ?>

    <?php // echo $form->field($model, 'jev1') ?>

    <?php // echo $form->field($model, 'jev2') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
