<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use dosamigos\datepicker\DatePicker;
use app\models\Members;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PayrollDividendsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payroll Dividends';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-dividends-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'checkboxOptions' => function($model) {
                            return ['disabled' => $model->jev?true:false];
                        },
            ],
            [
                'attribute'=>'date_from',
                'label'=>'Date',
                'filter' =>DatePicker::widget([
                    'model'=>$searchModel,
                    'attribute'=>'date_from',
                    'clientOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]),
                'value'=>function($model){
                    return date('F j, Y',strtotime($model->date_from));
                }
            ],
            [
                'attribute'=>'emp_id',
                'label'=>'Members',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>\yii\helpers\ArrayHelper::map(Members::find()->all(), 'id', 'fulllist'),
                'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '...'],
                'value'=>function($model){
                    return $model->emp->fulllist;
                }
            ],
            [
                'attribute'=>'hourly_rate',
                'label'=>'Amount',
                'value'=>function($model){
                    return $model->hourly_rate;
                }
            ],
            //'date_to',
            //'number_of_hours',
            //'date_created',
            'dividends_payable_jev',
            'dv',
            'pcv',
            'jev',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-plus"></i> Create',['create'],[ 'title' => 'Add New Member', 'class' => 'showModal btn btn-success','style'=>'color:white;']).'</div><div class="btn-group">'.Html::button('<i class="glyphicon glyphicon-send"></i> Create Dividends Payable',[ 'title' => 'Create Dividends Payable', 'class' => 'createpayable btn btn-primary','style'=>'color:white;']).'</div><div class="btn-group">'.Html::button('<i class="glyphicon glyphicon-send"></i> Create DV',[ 'title' => 'Create DV', 'class' => 'createjev btn btn-danger','style'=>'color:white;']).'</div><div class="btn-group">'.'</div><div class="btn-group">'.Html::button('<i class="glyphicon glyphicon-send"></i> Create PCV',[ 'title' => 'Create PCV', 'class' => 'createpcv btn btn-warning','style'=>'color:white;']).'</div><div class="btn-group">'.'<input type="date" id="transaction_date" class="form-control" value="'.date('Y-m-d').'">'
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
                'showPageSummary' => true,
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT
                ],
        ]); ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".createjev").click(function(){
            var selection = $('#w0').yiiGridView('getSelectedRows');
            var transaction_date = $('#transaction_date').val();
            if((selection.length)>0&&transaction_date!=null){
               var x = confirm("Are you sure you want to create a DV?");
               if(x==true){
                    $.ajax({
                          url    : '/pos/frontend/web/payroll-dividends/createjev',
                          type   : 'POST',
                          data   : {selection:selection,transaction_date:transaction_date},
                      });
               } 
            }
        });

        $(".createpcv").click(function(){
            var selection = $('#w0').yiiGridView('getSelectedRows');
            var transaction_date = $('#transaction_date').val();
            if((selection.length)>0&&transaction_date!=null){
               var x = confirm("Are you sure you want to create a PCV?");
               if(x==true){
                    $.ajax({
                          url    : '/pos/frontend/web/payroll-dividends/createpcv',
                          type   : 'POST',
                          data   : {selection:selection,transaction_date:transaction_date},
                      });
               } 
            }
        });

        $(".createpayable").click(function(){
            var selection = $('#w0').yiiGridView('getSelectedRows');
            var transaction_date = $('#transaction_date').val();
            if((selection.length)>0&&transaction_date!=null){
               var x = confirm("Are you sure you want to create a Dividends Payable Journal Entry?");
               if(x==true){
                    $.ajax({
                          url    : '/pos/frontend/web/payroll-dividends/createpayable',
                          type   : 'POST',
                          data   : {selection:selection,transaction_date:transaction_date},
                      });
               } 
            }
        });
    });
</script>