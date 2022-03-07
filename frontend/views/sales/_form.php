<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Customers;
use app\models\Products;
use app\models\OrArTracking;

/* @var $this yii\web\View */
/* @var $model app\models\Sales */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .subtotal{
        text-align: right;
        font-size:60px;
        height: 60px;
        background-color: #fffee1;
    }
    .subtotal[readonly]{
        background-color: #fffee1;
    }
</style>
<div class="row">
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading">Create New Sales</div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'transaction_date')->widget(DatePicker::classname(), [
                    'options'=>['value'=>date('Y-m-d'),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]) ?>
                
                <?php
                    echo $form->field($model, 'customer_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Customers::find()->all(), 'id', 'names'),
                        'options' => ['placeholder' => 'Select customer','value'=>1],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);

                ?>

                <?php
                    echo $form->field($salesmodel, 'product_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Products::find()->all(), 'id', 'product_name'),
                        'options' => ['placeholder' => 'Select a product'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'pluginEvents' => [
                            "select2:select" => "function()
                            {
                                var xhttp = new XMLHttpRequest();
                                xhttp.open('GET', '/pos/frontend/web/pricelist/getprice?pid='+$(this).val(), false);
                                xhttp.send();
                                document.getElementById('salesdetails-product_price').value = xhttp.responseText;
                                //alert($(this).val());                               
                            }",
                        ]
                    ]);

                ?>

                <?= $form->field($salesmodel, 'quantity')->textInput(['value'=>1,'class'=>'calculate_total form-control','type'=>'number']) ?>

                <?= $form->field($salesmodel, 'unit')->textInput(['maxlength' => true, 'value'=>'blocks']) ?>

                <?= $form->field($salesmodel, 'product_price')->textInput(['class'=>'calculate_total form-control']) ?>

                

                <?= $form->field($model, 'amount_paid')->textInput(['class'=>'calculate form-control']) ?>

                

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>

                
                </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading">&nbsp;</div>
            <div class="panel-body">
                <?= $form->field($model, 'total')->textInput(['readOnly' => true, 'class'=>'form-control subtotal','value'=>0])->label('Total Amount Due') ?>
                <?= $form->field($model, 'sales_on_credit')->textInput(['readOnly' => true, 'class'=>'form-control subtotal','value'=>0]) ?>
            </div>
        </div>
        <p>
            <?= $form->field($model, 'tempor')->textInput(['style'=>'width:40%','class'=>'form-control','value'=>OrArTracking::getNewor()]) ?>
        </p>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    setTimeout( function() { 
      jQuery( '#sales-customer_id').focus();
    }, 100 );
    $( ".calculate" ).keyup(function() {
      $("#sales-sales_on_credit").val($("#sales-total").val()-$("#sales-amount_paid").val());
    });
    $( ".calculate_total" ).keyup(function() {
      $("#sales-total").val($("#salesdetails-quantity").val()*$("#salesdetails-product_price").val());
    });
</script>