<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Officers;
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
    <div class="col-md-offset-3 col-md-6 col-md-offset-3">
        <div class="col-md-6">
            <?= $form->field($model, 'date_from')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'date_to')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-12">
            <?php
                echo $form->field($model, 'emp_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Officers::find()->joinWith('m')->all(), 'id', 'fulllist'),
                    'options' => ['placeholder' => 'Select officers ...'],
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
        $deductions = PayslipDeductionTypes::find()->where(['id'=>1])->all();
        foreach ($deductions as $key) {
            echo '<div class="col-md-12">';
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
    <?php ActiveForm::end(); ?>

</div>