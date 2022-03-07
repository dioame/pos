<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Pricelist;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'sku',
            'category0.category',
            'product_name',
            [
                'attribute'=>'price',
                'format'=>'raw',
                'hAlign'=>'center',
                'value'=>function($model){
                    $string = "";
                    $pricelist = Pricelist::find()->where(['pId'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
                    if($pricelist){
                        return Html::a('<small class="label label-danger">'.number_format($pricelist->price,2).'</small> ', ['pricelist/create', 'pID' => $model->id], [
                                    'class' => '',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to adjust the price?',
                                        'method' => 'post',
                                    ],
                                ]);
                    }
                    
                }
            ],
            //'buying_price',
            //'price',
            //'recommended_supplier',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                ['content'=>Html::a('Create Product', ['create'], ['class' => 'btn btn-success'])
                ],
                '{export}',
                '{toggleData}'
            ],
            'pjax' => false,
            'bordered' => true,
            'striped' => false,
            'condensed' => false,
            'responsive' => true,
            'hover' => true,
            'showPageSummary' => false,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT
            ],
    ]); ?>
</div>
<script type="text/javascript">
    $(function(){
          $(document).on('click', '.showModal', function(){
                $('#genericmodal').modal('show')
                        .find('#modal-body')
                        .load($(this).attr('value'));
                document.getElementById('modal-title').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
                setTimeout( function() { 
                  $('#jeventries-accounting_code').select2("open"); // Change the value or make some change to the internal state
                  //$('#jeventries-accounting_code').trigger('change.select2'); // Notify only Select2 of changes
                }, 500 );
        });
            
    });
</script>