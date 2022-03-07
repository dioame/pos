<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Uacs;

/* @var $this yii\web\View */
/* @var $model app\models\Ppe */
/* @var $form yii\widgets\ActiveForm */
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .summary{
        font-size: 2em;width: 100%;
        background-color: #fffbe4;
        border:none;
        padding:10px;
        text-align: right;
    }
</style>
<div class="row">
    <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'date_acquired')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-m-d',
                            'todayHighlight' => true
                        ],
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?php

                    echo $form->field($model, 'uacs')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Uacs::find()->where(['sub_class'=>'Property, Plant and Equipment','status'=>'Active'])->andWhere(['!=','object_code',''])->andWhere(['NOT LIKE','object_code','Accumulated Depreciation'])->andWhere(['NOT LIKE','object_code','Accumulated Impairment'])->all(), 'uacs', 'object_code','grouping'),
                        //'data' => ['1'=>['1.1'=>1.1],'2'=>['2.1'=>2.1]],
                        'options' => ['placeholder' => 'Select PPE type...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);

                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'particular')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'quantity')->textInput(['type'=>'number','step'=>0.01,'value'=>0]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'unit_cost')->textInput(['type'=>'number','step'=>0.01,'value'=>0]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?php
                    echo $form->field($model, 'fund_source')->widget(Select2::classname(), [
                        'data' => [10101000=>'Cash on hand',10101020=>'Petty Cash',10102010=>'Cash in Bank',20101010=>'Accounts Payable'],
                        'options' => ['placeholder' => 'Select fund source...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'pluginEvents' => [
                            'change' => 'function() {
                                if($(this).val()==20101010) {
                                    $("#additionalhere").html("<p><i>Please indicate additional information needed for Accounts Payable</i></p>");
                                    $("#additionalhere").append(\'<div class="col-md-6"><label class="form-label">Invoice Date</label><input type="date" class="form-control" name="accountspayableinvoice-invoice_date" ></div>\');
                                    $("#additionalhere").append(\'<div class="col-md-6"><label class="form-label">Due Date</label><input type="date" class="form-control" name="accountspayableinvoice-due_date" ></div>\');
                                    $("#additionalhere").append(\'<div class="col-md-6"><label class="form-label">Invoice #</label><input type="text" class="form-control" name="accountspayableinvoice-invoice_number" ></div>\');
                                    $("#additionalhere").append(\'<div class="col-md-6"><label class="form-label">PO #</label><input type="text" class="form-control" name="accountspayableinvoice-po_number" ></div>\');
                                    $("#additionalhere").append(\'<div class="col-md-12"><label class="form-label">Supplier</label><select class="form-control" id="supplierselector" name="accountspayableinvoice-supplier"><select/></div>\');
                                    $("#supplierselector").select2();
                                    var studentSelect = $(\'#supplierselector\');
                                    studentSelect.select2({
                                      ajax: {
                                        url: \'/pos/frontend/web/suppliers/allsuppliers\',
                                        processResults: function (data) {
                                          return {
                                            results: data.items
                                          };
                                        }
                                      }
                                    });
                                    
                                }else{
                                    $("#additionalhere").children().attr("disabled","disabled");
                                    $("#additionalhere").children().attr("hidden","true");
                                }
                            }
                            '
                        ]
                    ]);

                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'eul')->textInput(['type'=>'number','value'=>1]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'warranty_period')->widget(DatePicker::classname(), [
                        'type' => DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-m-d',
                            'todayHighlight' => true
                        ],
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'receipt_number')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        
    </div>
    <div class="col-md-6">
        <label>Total Cost</label>
        <input type="number" step="0.01" id="total_cost" class="summary" readonly><hr>
        <label>Annual Depreciation</label>
        <input type="number" step="0.01" id="annual_dep" class="summary" readonly>
        <hr>
        <div id="additionalhere">
            
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    $(":input").bind('keyup mouseup', function () {
        var total = $("#ppe-quantity").val()*$("#ppe-unit_cost").val();  
        var depreciation = $("#total_cost").val()/($("#ppe-eul").val());  
        $("#total_cost").val(total.toFixed(2));  
        $("#annual_dep").val(depreciation.toFixed(2));  
    });
</script>