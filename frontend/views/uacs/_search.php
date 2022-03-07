<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UacsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uacs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'classification') ?>

    <?= $form->field($model, 'sub_class') ?>

    <?= $form->field($model, 'grouping') ?>

    <?= $form->field($model, 'object_code') ?>

    <?= $form->field($model, 'uacs') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'isEnabled') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
