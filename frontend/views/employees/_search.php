<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EmployeesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employees-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lastname') ?>

    <?= $form->field($model, 'firstname') ?>

    <?= $form->field($model, 'date_started') ?>

    <?= $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'contact_number') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'position') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
