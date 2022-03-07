<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Employees;
use app\models\PayslipDeductionTypes;

/* @var $this yii\web\View */
/* @var $model app\models\Payroll */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .summary{
        font-size: 2em;width: 100%;
        background-color: #fffbe4;
        border:none;
        padding:10px;
        text-align: right;
    }
</style>
<div class="payroll-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-8">
        <div class="col-md-3">
            <?= $form->field($model, 'date_from')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'date_to')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'number_of_hours')->textInput(['type'=>'number','value'=>0]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'hourly_rate')->textInput(['type'=>'number','value'=>0]) ?>
        </div>
        <div class="col-md-12">
            <?php
                echo $form->field($model, 'emp_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Employees::find()->all(), 'id', 'fulllist'),
                    'options' => ['placeholder' => 'Select employees ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true
                    ],
                ]);

            ?>
            <hr>
        </div>
        <div class="col-md-12">
            <h2>Deductions</h2><br>
        </div>
    

    <?php
        $deductions = PayslipDeductionTypes::find()->all();
        foreach ($deductions as $key) {
            echo '<div class="col-md-3">';
            echo $form->field($deductionModel, 'dID')->textInput(['type'=>'number','value'=>0,'class'=>'form-control deductioninput','name'=>"PayrollDeductions[".$key->id."]"])->label($key->type);
            echo '</div>';
        }
    ?>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h1 class="pull-right">Gross Income</h1>
        <h1><input type="text" id="gross" class="summary" readonly></h1>
        <br>
        <h1 class="pull-right">Total Deduction</h1>
        <h1><input type="text" id="totaldeduction" name="" class="summary" readonly></h1>
        <br>
        <h1 class="pull-right">Net Income</h1>
        <h1><input type="text" id="netincome" name="" class="summary" readonly></h1>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(":input").bind('keyup mouseup', function () {
        var gross = $("#payroll-number_of_hours").val()*$("#payroll-hourly_rate").val();
        var totaldeduction = 0;         
        $(".deductioninput").each(function(){
            totaldeduction += parseFloat($(this).val());
        });
        $("#gross").val(gross.toFixed(2));  
        $("#totaldeduction").val(totaldeduction.toFixed(2));  
        $("#netincome").val((gross-totaldeduction).toFixed(2));  
    });
</script>