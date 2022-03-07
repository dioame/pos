<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use kartik\datetime\DateTimePicker;
use fedemotta\datatables\DataTables;
use kartik\select2\Select2;
use app\models\StockCard;
use app\models\Products;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .center{
        text-align: center;
    }
    th{
        text-align: center;
        padding: 5px;
        border: 1px solid black;
    }
    td{
        padding: 5px;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }
</style>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <div class="panel panel-default">
              <div class="panel-heading">Add New Production</div>
              <div class="panel-body">
                <?php $form = ActiveForm::begin(['action' =>['products/create']]); ?>
                <?php
                    echo $form->field($model, 'sku')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Products::find()->all(), 'sku', 'product_name'),
                        'options' => ['placeholder' => 'Select product ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]);

                ?>

                <?= $form->field($model, 'remarks')->dropDownList([ 'Batch 1' => 'Batch 1', 'Batch 2' => 'Batch 2', 'Batch 3' => 'Batch 3'], ['options' => ['Batch 1' => ['Selected'=>'selected']]])->label(false) ?>


                <?= $form->field($model, 'quantity_at_hand')->textInput(['placeholder'=>'Quantity'])->label(false) ?>

                <div class="row">
                    <div class="col-md-5">
                    <?php
                    echo $form->field($model, 'date')->widget(DatePicker::classname(), [
                        'clientOptions' => [
                            'autoclose' => true
                        ]
                    ]);

                    ?>
                    </div>
                    <div class="col-md-2 no-padding">
                        <label class="form-label">Time</label>
                        <select class="form-control">
                            <?php
                                for($i=1;$i<=12;$i++){
                                    if($i<10){
                                        $val = sprintf("%02d", $i);
                                    }else{
                                        $val = $i;
                                    }
                                    echo '<option>'.$val.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 no-padding">
                        <label class="form-label">&nbsp;</label>
                        <select class="form-control">
                            <?php
                                for($i=0;$i<=59;$i++){
                                    if($i<10){
                                        $val = sprintf("%02d", $i);
                                    }else{
                                        $val = $i;
                                    }
                                    echo '<option>'.$val.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3" style="padding-right: 10px;">
                        <label class="form-label">&nbsp;</label>
                        <select class="form-control">
                            <option>AM</option>
                            <option>PM</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">
                    <?php
                    echo $form->field($model, 'finished')->widget(DatePicker::classname(), [
                        'clientOptions' => [
                            'autoclose' => true
                        ]
                    ]);

                    ?>
                    </div>
                    <div class="col-md-2 no-padding">
                        <label class="form-label">Time</label>
                        <select class="form-control">
                            <?php
                                for($i=1;$i<=12;$i++){
                                    if($i<10){
                                        $val = sprintf("%02d", $i);
                                    }else{
                                        $val = $i;
                                    }
                                    echo '<option>'.$val.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2 no-padding">
                        <label class="form-label">&nbsp;</label>
                        <select class="form-control">
                            <?php
                                for($i=0;$i<=59;$i++){
                                    if($i<10){
                                        $val = sprintf("%02d", $i);
                                    }else{
                                        $val = $i;
                                    }
                                    echo '<option>'.$val.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3" style="padding-right: 10px;">
                        <label class="form-label">&nbsp;</label>
                        <select class="form-control">
                            <option>AM</option>
                            <option>PM</option>
                        </select>
                    </div>
                </div>                

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
              </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
                <p><i>Most recent production added...</i></p>
                <table id="exportTable" width="100%" border="0" style="border-bottom: 1px solid black;">
                    <tr>
                        <th>Date</th>
                        <th>Particulars</th>
                        <th>Quanity</th>
                    </tr>
        <?php

                    $all= [];
                    $products = Products::find()->all();
                    foreach ($products as $products) {
                        $stocks = StockCard::find()->where(['sku'=>$products->sku])->orderBy(['date'=>SORT_DESC,'id'=>SORT_DESC])->limit(10)->all();
                        
                        foreach ($stocks as $stocks) {
                            array_push($all,[$stocks->date,$stocks->id,'stocks']);
                        }
                    }

                    foreach ($all as $all) {
                        echo '<tr>';
                            $stockcard = StockCard::findOne($all[1]);
                            echo '<td>'.date('m/j/Y',strtotime($all[0])).'</td>';
                            echo '<td>Daily production of '.$stockcard->product->product_name.' '.$stockcard->remarks.'</td>';
                            echo '<td class="center">'.$stockcard->added.'</td>';
                    }
        ?>
                </table>
    </div>
</div>
<script>
function setFocusToTextBox(){
    document.getElementById("products-date").focus();
}
$( "#products-sku" ).change(function() {
    $.ajax({
        url    : '/pos/frontend/web/products/searchsku',
        type   : 'GET',
        data   : {id:$(this).val()},
        success: function (response) 
        {
            var obj = jQuery.parseJSON(response);
            if(obj.code="success"){
                $("#products-product_name").val(obj.product_name);
                $("#products-quantity_at_hand").val(0);
                $("#products-buying_price").val(obj.buying_price);
                $("#products-price").val(obj.price);  
            }            
        },
        error  : function (e) 
        {
            alert("Error 103!");
        }
    });
});
</script>
<!-- 
<script type="text/javascript">
    $("#createForm").submit( function(e){
          var form = $(this);
          $.ajax({
                url    : '/pos/frontend/web/products/create',
                type   : 'POST',
                data   : form.serialize(),
                success: function (response) 
                {                  
                   console.log(response);
                },
                error  : function (e) 
                {
                    console.log(e);
                }
            });
        return false;        
      })
</script> -->