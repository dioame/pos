<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Uacs;
use app\models\Suppliers;
use app\models\DvTracking;

/* @var $this yii\web\View */
/* @var $model app\models\Expenses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'date_posted')->widget(DatePicker::classname(), [
                'options'=>['value'=>date('Y-m-d'),],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?php
                echo $form->field($model, 'debit')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Uacs::find()->where(['isEnabled'=>'1','classification'=>'Expenses'])->all(), 'uacs', 'object_code'),
                    'options' => ['placeholder' => 'Select type of expense...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);

            ?>
            <span><?= $form->field($model, 'dv_number')->hiddenInput(['style'=>'text-align:right;','maxlength' => true,'value'=>($model->dv_number?$model->dv_number:DvTracking::getNewdvnumber())])->label(false) ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'quantity')->textInput(['value'=>1,'class'=>'calculate_total form-control']) ?>
        </div>
        
        <div class="col-md-4">
            <?= $form->field($model, 'unit_cost')->textInput(['value'=>0,'class'=>'calculate_total form-control']) ?>
        </div>
        
        <div class="col-md-5">
            <?= $form->field($model, 'amount')->textInput(['class'=>'calculate form-control']) ?>
        </div>
    </div>
    

    <?php
        echo $form->field($model, 'payee')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Suppliers::find()->all(), 'id', 'supplier_name'),
            'options' => ['placeholder' => 'Select supplier...','value'=>1],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?= $form->field($model, 'particular')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<script type="text/javascript">
    setTimeout( function() { 
      jQuery( '#dvtracking-date').focus();
    }, 100 );
    $( ".calculate" ).keyup(function() {
      $("#dvtracking-balance").val($("#dvtracking-amount").val()-$("#dvtracking-amount_paid").val());
    });
    $( ".calculate_total" ).keyup(function() {
      $("#dvtracking-amount").val($("#dvtracking-quantity").val()*$("#dvtracking-unit_cost").val());
      $("#dvtracking-particular").val('Payment of '+$("#dvtracking-debit :selected").text());
    });
</script>

