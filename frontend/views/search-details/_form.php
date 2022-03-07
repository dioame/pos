<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SalesDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-details-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sales_id')->textInput() ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'product_price')->textInput() ?>

    <?= $form->field($model, 'sub_total')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
