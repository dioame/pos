<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Suppliers;

/* @var $this yii\web\View */
/* @var $model app\models\AccountsPayableInvoiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="accounts-payable-invoice-search">
    <hr>
    <div class="row">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="col-md-1">
            <?= $form->field($model, 'id') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'invoice_number') ?>
        </div>
        <div class="col-md-2">
            <?php
                echo $form->field($model, 'supplier')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Suppliers::find()->all(), 'id', 'supplier_name'),
                    'options' => ['placeholder' => 'Select supplier...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);

            ?>
        </div>
        <div class="col-md-2">
            <label class="form-label">Date From</label>
            <?= $form->field($model, 'date_from')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'options'=>['value'=>date('Y-m-d'),],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ])->label(false) ?>
        </div>
        <div class="col-md-2">
            <label class="form-label">Date From</label>
            <?= $form->field($model, 'date_to')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'options'=>['value'=>date('Y-m-d'),],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ])->label(false) ?>
        </div>
        <div class="col-md-1">
            <?= $form->field($model, 'jev') ?>
        </div>
        <?php // echo $form->field($model, 'po_number') ?>

        <?php // echo $form->field($model, 'type_of_expense') ?>

        <?php // echo $form->field($model, 'amount') ?>

        <?php // echo $form->field($model, 'jev') ?>

        <div class="form-group">
            <br>
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <hr>
</div>
