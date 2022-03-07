<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\AccountsPayableInvoice;
use app\models\Suppliers;
use app\models\Uacs;

/* @var $this yii\web\View */
/* @var $model app\models\AccountsPayableInvoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-offset-3 col-md-6 col-md-offset-3">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">

        <div class="col-md-6">
            <?= $form->field($model, 'invoice_date')->widget(DatePicker::classname(), [
                'options'=>['value'=>($model->invoice_date?$model->invoice_date:date('Y-m-d'))],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-d',
                    'todayHighlight' => true
                ],
                'pluginEvents' => [
                    "changeDate" => "function(e)
                    {
                        var d = e.date;
                        var month = ('0' + (d.getMonth()+1)).slice(-2);
                        var year = d.getFullYear();
                        var day = ('0'+d.getDate()).slice(-2);

                        newdate = year+'-'+month+'-'+day;
                        
                        newdate = newdate;
                        $('#accountspayableinvoice-due_date').val(newdate);
                       // $('#accountspayableinvoice-due_date').kvDatepicker('setStartDate',newdate);

                       
                    }",
                ]
            ]) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'due_date')->widget(DatePicker::classname(), [
                'options'=>['value'=>($model->due_date?$model->due_date:date('Y-m-d'))],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ]) ?>
        </div>
        </div>

        <?= $form->field($model, 'invoice_number')->textInput(['value'=>($model->invoice_number?$model->invoice_number:date('Y-m-'.(AccountsPayableInvoice::find()->count('id')+1)))]) ?>

        <?= $form->field($model, 'po_number')->textInput() ?>

        <?php
            echo $form->field($model, 'supplier')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Suppliers::find()->all(), 'id', 'supplier_name'),
                'options' => ['placeholder' => 'Select supplier...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);

        ?>

        <?php
            echo $form->field($model, 'type_of_expense')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Uacs::find()->where(['isEnabled'=>'1','classification'=>'Expenses'])->all(), 'uacs', 'object_code'),
                'options' => ['placeholder' => 'Select type of expense...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);

        ?>

        <?= $form->field($model, 'amount')->textInput(['type'=>'number','step'=>0.001]) ?>

        <div class="form-group">
            <center><?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?></center>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
