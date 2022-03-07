<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PpeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppe-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'uacs') ?>

    <?= $form->field($model, 'particular') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'unit_cost') ?>

    <?php // echo $form->field($model, 'date_acquired') ?>

    <?php // echo $form->field($model, 'eul') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
