<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use app\models\Products;
use app\models\Suppliers;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    $(".calculate").on("keyup", function(e, item) {
        var res = this.id.split("-");
      $("#purchasedetails-"+res[1]+"-total").val($("#purchasedetails-"+res[1]+"-quantity").val()*$("#purchasedetails-"+res[1]+"-cost").val());
        sumall();
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    $(".calculate").on("keyup", function(e, item) {
        var res = this.id.split("-");
      $("#purchasedetails-"+res[1]+"-total").val($("#purchasedetails-"+res[1]+"-quantity").val()*$("#purchasedetails-"+res[1]+"-cost").val());

    });
    sumall();
});
';

$this->registerJs($js);
?>

<div class="customer-form">
<div class="row">
    <div class="col-md-offset-1 col-md-10 col-md-offset-1">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
    <div class="row">
        <div class="col-sm-6">
            <?php
                echo $form->field($modelPurchases, "payee")->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Suppliers::find()->all(), 'id', 'supplier_name'),
                    'options' => ['placeholder' => 'Select supplier...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
            ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($modelPurchases, 'date_posted')->widget(DatePicker::classname(), [
                    'options'=>['value'=>date('Y-m-d'),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]) ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-shopping-cart"></i> Items</h4></div>
        <div class="panel-body">
             <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                'limit' => 100, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelDetails[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'purchase_id',
                    'product_id',
                    'quantity',
                    'unit',
                    'cost',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <div class="row">
                <div class="col-md-12">
                <div class="col-sm-4">
                    Product
                </div>
                <div class="col-sm-2">
                    Qty
                </div>
                <div class="col-sm-1">
                    Unit
                </div>
                <div class="col-sm-2">
                    Unit Cost
                </div>
                <div class="col-sm-1">
                    Total
                </div>
            </div>
            </div>
            <?php foreach ($modelDetails as $i => $modelAddress): ?>
                <div class="item panel1 panel-default"><!-- widgetBody -->
                    <div class="panel-body" style="padding-top: 0px;padding-bottom: 0px;">
                        <?php
                            // necessary for update action.
                            if (! $modelAddress->isNewRecord) {
                                echo Html::activeHiddenInput($modelAddress, "[{$i}]id");
                            }
                        ?>
                    
                        <div class="row">
                            <div class="col-sm-4">
                                <?php
                                    echo $form->field($modelAddress, "[{$i}]product_id")->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(Products::find()->all(), 'id', 'product_name'),
                                        'options' => ['placeholder' => 'Select product...'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ])->label(false);

                                ?>
                               
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelAddress, "[{$i}]quantity")->textInput(['maxlength' => true,'type'=>'number','step'=>0.01, 'class'=>'form-control calculate'])->label(false) ?>
                            </div>
                            <div class="col-sm-1">
                                <?= $form->field($modelAddress, "[{$i}]unit")->textInput(['maxlength' => true])->label(false) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelAddress, "[{$i}]cost")->textInput(['maxlength' => true,'type'=>'number','step'=>0.01, 'class'=>'form-control calculate'])->label(false) ?>
                            </div>
                            <div class="col-sm-2">
                                <?= $form->field($modelAddress, "[{$i}]total")->textInput(['maxlength' => true,'type'=>'number','step'=>0.01,'readOnly'=>true, 'class'=>'form-control totalall'])->label(false) ?>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                            </div>
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <?php DynamicFormWidget::end(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelPurchases, "total")->textInput(['maxlength' => true,'type'=>'number','step'=>0.01,'value'=>0,'readOnly'=>true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelPurchases, "amount_paid")->textInput(['maxlength' => true,'type'=>'number','step'=>0.01,'value'=>0, 'class'=>'entryamount form-control']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($modelPurchases, "accounts_payable")->textInput(['maxlength' => true,'type'=>'number','step'=>0.01,'value'=>0,'min'=>0,'readOnly'=>true]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($modelPurchases->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
</div>
</div>
<script type="text/javascript">

    function calculateap(){
        $("#purchases-accounts_payable").val($("#purchases-total").val()-$("#purchases-amount_paid").val());
    }

    function sumall(){
        var sum=0;
        $('.totalall').each(function(){
            if($(this).val()){
                sum += parseFloat($(this).val());  // Or this.innerHTML, this.innerText
                $("#purchases-total").val(sum);
                calculateap();
            }
        });
    }
    $(".dynamicform_wrapper").on("beforeInsert", function(e, item) {
        console.log("beforeInsert");
    });

    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        console.log("afterInsert");
        alert("asd");
    });

    $(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
        if (! confirm("Are you sure you want to delete this item?")) {
            return false;
        }
        return true;
    });

    $(".dynamicform_wrapper").on("afterDelete", function(e) {
        console.log("Deleted item!");
    });

    $(".dynamicform_wrapper").on("limitReached", function(e, item) {
        alert("Limit reached");
    });
    $(".calculate").on("keyup", function(e, item) {
        var res = this.id.split("-");
      $("#purchasedetails-"+res[1]+"-total").val($("#purchasedetails-"+res[1]+"-quantity").val()*$("#purchasedetails-"+res[1]+"-cost").val());
      sumall();
    });
    $(".entryamount").on("keyup", function(e, item) {
      calculateap();
    });
</script>