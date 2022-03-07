<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Suppliers;
use app\models\DvTracking;
use app\models\Uacs;
use app\models\Employees;

/* @var $this yii\web\View */
/* @var $model app\models\DvTracking */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    td{
        padding: 6px;
    }
    tbody{
        border-color:#dedede;
    }
    table {
        display: table;
        border-collapse: collapse;
        border-spacing: 2px;
        border-color: #dedede;
    }
</style>
<div class="dv-tracking-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-offset-1 col-md-10 col-md-offset-1">

            <div class="col-md-offset-9 col-md-3">
                <span><?= $form->field($model, 'dv_number')->textInput(['style'=>'text-align:right;','maxlength' => true,'value'=>($model->dv_number?$model->dv_number:DvTracking::getNewdvnumber())]) ?></span>
            </div>

            <div class="col-md-6">
                <?php
                    echo $form->field($model, 'payee')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Suppliers::find()->all(), 'id', 'supplier_name'),
                        'options' => ['placeholder' => 'Select supplier ...','value'=>$apmodel->supplier],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);

                ?>
            </div>

            <div class="col-md-offset-3 col-md-3">
                <?= $form->field($model, 'date_posted')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'options'=>['value'=>date('Y-m-d'),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]) ?>
            </div>

            <!-- <div class="col-md-12">
                <?= $form->field($model, 'amount')->textInput() ?>
            </div> -->
            
            <div class="col-md-12">
                <table width="100%" border="1" style="background-color: white;">
                    <tr>
                        <td colspan="3" width="70%">For payment of:</td>
                        <td width="5%"></td>
                        <td style="text-align: center">AMOUNT</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 50px;"><?= $form->field($model, 'particular')->textInput()->label(false) ?></td>
                        <td>P</td>
                        <td class="rightni"><?= $form->field($model, 'amount')->textInput(['type'=>'number','step'=>0.001,'class'=>'rightni form-control'])->label(false) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">TOTAL</td>
                        <td>P</td>
                        <td class="rightni"><span id="totalhere">0.00</span></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">ACCOUNT TITLE</td>
                        <td style="text-align: center;">DEBIT</td>
                        <td></td>
                        <td style="text-align: center;">CREDIT</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;padding: 20px;"></td>
                        <td style="text-align: center;"><?= $apmodel->typeOfExpense->object_code ?></td>
                        <td></td>
                        <td style="text-align: center;"><?= Uacs::findOne($creditaccount)->object_code ?></td>
                    </tr>
                    <tr>
                        <td rowspan="3">
                            <?php
                                echo $form->field($model, 'prepared_by')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(Employees::find()->all(), 'id', 'fulllist'),
                                    'options' => ['placeholder' => 'Select employee ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);

                            ?>
                        </td>
                        <td rowspan="3">
                            <?php
                                echo $form->field($model, 'approved_by')->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(Employees::find()->all(), 'id', 'fulllist'),
                                    'options' => ['placeholder' => 'Select employee ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]);

                            ?>
                        </td>
                        <td style="text-align: center;">Form of payment</td>
                        <td colspan="2" rowspan="3">
                            <?= $form->field($model, 'received_by')->textInput(['value'=>$apmodel->supplier0->supplier_name]) ?>
                        </td>
                    </tr>
                    <tr><td>Cash</td></tr>
                    <tr><td>Check</td></tr>
                </table>
                <br>
            </div>

            <?= $form->field($model, 'type')->hiddenInput(['value'=>'AP'])->label(false) ?>

            <?= $form->field($model, 'debit')->hiddenInput(['value'=>$apmodel->type_of_expense])->label(false) ?>

            <?= $form->field($model, 'credit')->hiddenInput(['value'=>$creditaccount])->label(false) ?>

            <?= $form->field($model, 'requested_by')->hiddenInput()->label(false) ?>

            <div class="col-md-12">
                <div class="form-group pull-right">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<script type="text/javascript">
    $( "#dvtracking-amount" ).keyup(function() {
      $("#totalhere").html($("#dvtracking-amount").val());
    });
</script>