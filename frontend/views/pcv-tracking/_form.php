<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Uacs;
use app\models\Suppliers;

/* @var $this yii\web\View */
/* @var $model app\models\pettycash */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
    <div class="row">
    <center><h3><?= $this->title ?></h3><hr></center>
    <?php $form = ActiveForm::begin(); ?>

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
                'data' => ArrayHelper::merge(ArrayHelper::map(Uacs::find()->where(['isEnabled'=>'1','classification'=>'expenses'])->all(), 'uacs', 'object_code'),['10101020'=>'Petty Cash']),
                'options' => ['placeholder' => 'Select type of expense...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);

        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'quantity')->textInput(['value'=>1,'class'=>'calculate_total form-control']) ?>
    </div> 
    <div class="col-md-4">
        <?= $form->field($model, 'unit_cost')->textInput(['value'=>0,'class'=>'calculate_total form-control']) ?>
    </div> 
    <div class="col-md-4">
        <?= $form->field($model, 'amount')->textInput(['class'=>'calculate form-control','readOnly' => true]) ?>
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
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<script type="text/javascript">
    setTimeout( function() { 
      jQuery( '#pettycash-date').focus();
    }, 100 );
    $( ".calculate" ).keyup(function() {
      $("#pcvtracking-balance").val($("#pcvtracking-amount").val()-$("#pcvtracking-amount_paid").val());
    });
    $( ".calculate_total" ).keyup(function() {
      $("#pcvtracking-amount").val($("#pcvtracking-quantity").val()*$("#pcvtracking-unit_cost").val());
      $("#pcvtracking-particular").val('Payment of '+$("#pcvtracking-debit :selected").text());
    });
</script>

