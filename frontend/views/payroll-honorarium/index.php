<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use dosamigos\datepicker\DatePicker;
use app\models\Officers;
use app\models\PayrollDeductions;
use app\models\PayslipDeductionTypes;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PayrollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payroll';
$this->params['breadcrumbs'][] = $this->title;

$types = PayslipDeductionTypes::findOne(1);
$columns = []; 
$columns[]=['class' => 'kartik\grid\SerialColumn'];
$columns[]=[
    'class' => '\kartik\grid\CheckboxColumn',
    'checkboxOptions' => function($model) {
                return ['disabled' => $model->jev?true:false];
            },
    ];
$columns[]=[
    'attribute'=>'date_from',
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
];
$columns[]=[
    'attribute'=>'date_to',
    'filter' =>DatePicker::widget([
        'model'=>$searchModel,
        'attribute'=>'date_to',
        'clientOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-m-d',
            'todayHighlight' => true
        ],
    ]),
    'value'=>function($model){
        return date('F j, Y',strtotime($model->date_to));
    }
];
$columns[]=[
    'attribute'=>'emp_id',
    'filterType' => GridView::FILTER_SELECT2,
    'filter' =>\yii\helpers\ArrayHelper::map(Officers::find()->all(), 'id', 'fulllist'),
    'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => '...'],
    'value'=>function($model){
        return $model->emp->fulllist;
    }
];
$columns[]=[
    'label'=>'Gross Income',
    'value'=>function($model){
        return $model->number_of_hours*$model->hourly_rate;
    },
    'hAlign'=>'right',
    'format'=>'decimal',
    'pageSummary'=>true,
    'pageSummaryFunc'=>GridView::F_SUM

];
$columns[] = [
    'label'=>'Late & Absences',
    'format' => 'decimal',
    'value' => function($model) use ($types){

        $deduction = PayrollDeductions::find()->where(['pID'=>$model->id,'dID'=>$types->id])->one();
        if($deduction)
        return $deduction->amount;
    },
    'pageSummary'=>true,
    'pageSummaryFunc'=>GridView::F_SUM
];
$columns[]=[
    'label'=>'Total Deduction',
    'value'=>function($model){
        return PayrollDeductions::find()->where(['pID'=>$model->id])->sum('amount');
    },
    'hAlign'=>'right',
    'format'=>'decimal',
    'pageSummary'=>true,
    'pageSummaryFunc'=>GridView::F_SUM

];
$columns[]=[
    'label'=>'Net Income',
    'value'=>function($model){
        return ($model->number_of_hours*$model->hourly_rate)-PayrollDeductions::find()->where(['pID'=>$model->id])->sum('amount');
    },
    'hAlign'=>'right',
    'format'=>'decimal',
    'pageSummary'=>true,
    'pageSummaryFunc'=>GridView::F_SUM

];
$columns[] = 'jev'; 
$columns[] = 'dv'; 
$columns[] = 'pcv'; 
//'hourly_rate',

$columns[]=['class' => 'kartik\grid\ActionColumn',
  'template'=>'{delete}', 
  'visibleButtons'=>['delete'=>function($model,$key,$index){return $model->jev?false:true;}]                       
];

?>
<div class="payroll-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' =>$columns,
        'toolbar' =>  [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Create',['create'],[ 'title' => 'Add New Member', 'class' => 'showModal btn btn-success','style'=>'color:white;']).'</div><div class="btn-group">'.Html::button('<i class="glyphicon glyphicon-send"></i> Create DV',[ 'title' => 'Create DV', 'class' => 'createjev btn btn-danger','style'=>'color:white;']).'</div><div class="btn-group">'.'</div><div class="btn-group">'.Html::button('<i class="glyphicon glyphicon-send"></i> Create PCV',[ 'title' => 'Create PCV', 'class' => 'createpcv btn btn-warning','style'=>'color:white;']).'</div><div class="btn-group">'.'<input type="date" id="transaction_date" class="form-control" value="'.date('Y-m-d').'">'
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
               var x = confirm("Are you sure you want to create a Journal Entry?");
               if(x==true){
                    $.ajax({
                          url    : '/pos/frontend/web/payroll-honorarium/createjev',
                          type   : 'POST',
                          data   : {selection:selection,transaction_date:transaction_date},
                      });
               } 
            }
        });
    });
    $(".createpcv").click(function(){
            var selection = $('#w0').yiiGridView('getSelectedRows');
            var transaction_date = $('#transaction_date').val();
            if((selection.length)>0&&transaction_date!=null){
               var x = confirm("Are you sure you want to create a Journal Entry?");
               if(x==true){
                    $.ajax({
                          url    : '/pos/frontend/web/payroll-honorarium/createpcv',
                          type   : 'POST',
                          data   : {selection:selection,transaction_date:transaction_date},
                      });
               } 
            }
        });
</script>