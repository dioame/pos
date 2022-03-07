<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use fedemotta\datatables\DataTables;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">
    <div class="col-md-8">
        <div class="form-group">
            <div class="panel panel-default">
              <div class="panel-heading">List of all Products</div>
              <div class="panel-body">
                <?php Pjax::begin(); ?>
                <?= DataTables::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id',
                        'sku',
                        'product_name',
                        'quantity_at_hand',
                        //'buying_price',
                        'price',
                        ['class' => 'yii\grid\ActionColumn',
                          'template'=>'{add}',
                            'buttons'=>[
                              'add' => function ($url, $model) {     
                                return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
                                        'title' => Yii::t('yii', 'Create'),
                                ]);                                
            
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
              <div class="panel-heading">New Order</div>
              <div class="panel-body">
              </div>
            </div>
        </div>
        <div class="form-group">
            <div class="panel panel-default">
              <div class="panel-body">
                <p>Due:<span class="pull-right"><b>0.00</b></span></p><hr>
                <p>Change:<span class="pull-right"><b>0.00</b></span></p>
                
              </div>
              <div class="panel-footer" style="padding: 0px;">
                <button type="submit" class="btn btn-primary" style="width:100%">Pay Now</button>
              </div>
            </div>
        </div>
    </div>
</div>
<script>
function setFocusToTextBox(){
    document.getElementById("products-sku").focus();
}
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