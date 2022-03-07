<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use fedemotta\datatables\DataTables;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Customers;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expenses';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    a{
        cursor: pointer;
    }
    .dueamount{
      border: 0px;
      text-align: right;
      background-color: #f9f9e8;
    }
    .dueamount:focus{
      outline: none;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
</style>
<div class="products-index">
    <div class="col-md-8">
        <div class="form-group">
            <div class="panel panel-default">
              <div class="panel-heading">List of all Expenses</div>
              <div class="panel-body">
                <?php Pjax::begin(); ?>
                <?= DataTables::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id',
                        'name',
                        //'buying_price',
                        [
                         'attribute'=>'amount',
                         'format' => ['decimal',2],
                         'value' => function($model){
                                return $model->amount;
                            }
                        ],
                        ['class' => 'yii\grid\ActionColumn',
                          'template'=>'{add}',
                            'buttons'=>[
                              'add' => function ($url, $model) {     
                                return '<center><a class="btn btn-success additem" id="'.$model->id.'"><span class="glyphicon glyphicon-plus"></span></a></center>';
                              }
                          ]                            
                        ],
                    ],
                ]);?>


                <?php Pjax::end(); ?>
              </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <div class="panel panel-default">
              <div class="panel-heading">New Expense</div>
              <div id="item-list" class="panel-body list-group" style="padding: 0px;">
              </div>
              <div class="panel-footer" style="height: 100%;">
                <p style="margin-bottom: 0px;font-size: 40px;"><small>Total: &#8369;</small><b><span id="totalhere" class="pull-right" style=""> 0.00</span></b></p>
              </div>
            </div>
        </div>
        <div class="form-group">
            <div class="panel panel-default">
              <div class="panel-heading">Other Details</div>
              <div class="panel-body">
                <label class="form-label">Transaction Date</label>
                <?php
                  echo DatePicker::widget([
                    'id' => 'transaction_date', 
                    'name' => 'transaction_date', 
                    'value' => date('Y-m-d'),
                    'options' => ['placeholder' => 'Select transaction date ...'],
                    'pluginOptions' => [
                      'autoclose'=>true,
                      'format' => 'yyyy-m-d',
                      'todayHighlight' => true
                    ]
                  ]);
                ?>
                <br>
                <label class="form-label">Form of Payment</label>
                <?php
                  echo Select2::widget([
                      'name' => 'customerid',
                      'value' => 'cash',
                      'data' => ['cash'=>'Cash','petty cash fund'=>'Petty Cash Fund','accounts payable'=>'Accounts Payable'],
                      'options' => ['placeholder' => 'Select payment ...']
                  ]);
                ?><br>
                <label class="form-label">Payee</label>
                <input type="text" id="payee" class="form-control" style="width:100%;" />
                <br>
                <label class="form-label">Remarks</label>
                <input type="text" id="remarks" class="form-control" style="width:100%;" />
              </div>
            </div>
        </div>
        <div class="form-group">
            <div class="panel panel-default">
              <div class="panel-body">
                <p>Amount (F3):<p><span class="pull-right" style="font-size: 40px;"><b><input class="dueamount number" type="number" style="width: 100%;" placeholder="0.00" value=".00" /></b></span></p><hr style="margin: 0px;">
                <p>Balance:<b><span id="changehere" class="pull-right" style="font-size: 30px;">&#8369; 0.00</span></b></p>
              </div>
              <div class="panel-footer" style="padding: 0px;">
                <button type="submit" class="btn btn-primary" style="width:100%;padding:20px;">Pay (F4)</button>
              </div>
            </div>
        </div>
    </div>
</div>
<script>
var paid;
  setTimeout( function() { 
    jQuery( 'input[type="search"]').focus();
  }, 100 );
$(document).on('click','.additem', function(){
    //alert($(this).attr('id'));
    additem($(this).attr('id'));     
});

function additem(item_id){
  $.ajax({
        url    : '/pos/frontend/web/expenses/add',
        type   : 'POST',
        data   : {id:item_id},
        success: function (response) 
        {
            var obj = jQuery.parseJSON(response);
            if($("#" + obj.id + ".list-group-item").length == 0) {
              $("#item-list").append('<a class="list-group-item" id="'+obj.id+'"><b>'+obj.product_name+'<span class="pull-right">&#8369; <span class="price" id="'+obj.id+'">'+obj.price.toFixed(2)+'</span></b></span><br><small><span class="quantity" id="'+obj.id+'">1</span> @ &#8369; <span class="subtotal" id="'+obj.id+'">'+obj.price.toFixed(2)+'</span></small></a>');
            }else{
              $("#" + obj.id + ".list-group-item").html('<b>'+obj.product_name+'<span class="pull-right">&#8369; <span class="price" id="'+obj.id+'">'+((parseFloat($("#" + obj.id+".price").html())+obj.price).toFixed(2))+'</span></b></span><br><small><span class="quantity" id="'+obj.id+'">'+(parseFloat($("#" + obj.id+".quantity").html())+1)+'</span> @ &#8369; <span class="subtotal" id="'+obj.id+'">'+obj.price.toFixed(2)+'</span></small>');
            }
            var sum = 0;
            $( ".price" ).each(function() {
              sum += parseFloat($(this).html());
            });  
            $("#totalhere").html(sum.toFixed(2));

            switch(obj.product_name){
              case 'Petty Cash Fund': $("#remarks").val('Establishment of Petty Cash Fund');break;
              default: $("#remarks").val('Payment of '+obj.product_name);break;
            }

            jQuery( 'input[type="search"]').focus();        
            generatetotal();
        },
        error  : function (e) 
        {
            alert('Error 101');
        }
    });
}

function generatetotal(){
  var sum=0;
  $( ".price" ).each(function() {
    $("#" + $(this).attr('id')+".price").html(($("#" + $(this).attr('id')+".quantity").html()*$("#" + $(this).attr('id')+".subtotal").html()).toFixed(2));
  });
  $( ".price" ).each(function() {
    sum += parseFloat($(this).html());
  });
  $("#totalhere").html((sum).toFixed(2));
  generatechange();
}

function generatechange(){
  var sum = 0;
  $( ".price" ).each(function() {
    sum += parseFloat($(this).html());
  });
  $("#changehere").html("&#8369; "+(parseFloat($(".dueamount").val())-sum).toFixed(2));
}

$('input.number').keyup(function(event) {
  var sum = 0;
  $( ".price" ).each(function() {
    sum += parseFloat($(this).html());
  });
  $("#changehere").html("&#8369; "+($(this).val()-sum).toFixed(2));
  
});
$(document).on('click','.quantity', function(){
    var person = prompt("Please enter number of quantity", $(this).html());
    if(person===""||person===null){

    }else{
      $(this).html(parseFloat(person));
    }
    generatetotal();
});
$(document).on('click','.subtotal', function(){
    var price = prompt("Please enter number of quantity", $(this).html());
    if(price===""||price===null){

    }else{
      $(this).html(parseFloat(price));
    }
    generatetotal();
});
$(document).keydown(function(){
    if(event.which == 113) { //F2
        jQuery( 'input[type="search"]').val('');
        jQuery( 'input[type="search"]').focus();
        return false;
    }else if(event.which == 112) {
        $('#w1').select2('open');
        return false;
    }
    else if(event.which == 114) { //F3
        jQuery( '.dueamount').focus();
        return false;
    }else if(event.which == 115) { //F4
        var x = confirm("Are you sure you want to pay now?");
        if(x!=true){
          return false;
        }else{
          payments(1);
        }
    }else if(event.which == 116) { //F5
        var x = confirm("Are you sure you want to create new transaction?");
        if(x!=true){
          return false;
        }else{
          payments(0);
        }
        //
    }else if(event.which == 13 && event.shiftKey) {
        var id = $("#datatables_w0").find(' tbody tr:eq(0)').attr('data-key');
        additem(id);
    }
});

function payments(paid){
  var items = [];
  var customerid=$("#w1").val();
  var transaction_date=$("#transaction_date").val();
  var payee=$("#payee").val();
  var remarks=$("#remarks").val();
  var amount_paid=parseFloat($(".dueamount").val());
  var totalhere=parseFloat($("#totalhere").html());
  var expenseid = $(".list-group-item").attr("id");
  if((totalhere==0)&&paid==1){
    alert("Amount paid or total is invalid!");
    jQuery( '.dueamount').focus();
  }else{
    //var url = '/pos/frontend/web/expenses/newtransaction';
    //$.post( url, { name: "John", time: "2pm" } );
    $.ajax({
          url    : '/pos/frontend/web/expenses/newtransaction',
          type   : 'POST',
          //$.post( url, { name: "John", time: "2pm" } );
          data   : {expenseid,amount_paid,totalhere,customerid,paidval:paid,transaction_date,payee,remarks},
          /*success: function (response) 
          {
              //var obj = jQuery.parseJSON(response);
              alert(response);        
              
          },
          error  : function (e) 
          {
              alert('Error 102');
          }*/
      });
  }
  
  //alert(JSON.stringify(items));
}
</script>