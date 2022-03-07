<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SalesDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sales-details-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sales_id') ?>

    <?= $form->field($model, 'product_id') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'product_price') ?>

    <?php // echo $form->field($model, 'sub_total') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
