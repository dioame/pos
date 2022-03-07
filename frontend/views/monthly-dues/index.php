<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MonthlyDuesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Monthly Dues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monthly-dues-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'mID',
                'value'=>function($model){return $model->m->fulllist;}
            ],
            [
                'attribute'=>'month',
                'value'=>function($model){return date('F',strtotime('2018-'.$model->month.'-1'));}
            ],
            'year',
            [
                'attribute'=>'amount',
                'format'=>['decimal',2]
            ],
            'jev',

            ['class' => 'kartik\grid\ActionColumn',
              'template'=>'{delete}', 
              'visibleButtons'=>['delete'=>function($model,$key,$index){return $model->jev?false:true;}]                       
            ],
        ],
        'toolbar' =>  [
                    ['content'=>
                        Html::a('Receive Monthly Due', ['create'], ['class' => 'btn btn-success'])
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
    $(document).ready(function(){
        $(".createjev").click(function(){
            var selection = $('#w0').yiiGridView('getSelectedRows');
            var transaction_date = $('#transaction_date').val();
            if((selection.length)>0&&transaction_date!=null){
               var x = confirm("Are you sure you want to create a Journal Entry?");
               if(x==true){
                    $.ajax({
                          url    : '/pos/frontend/web/monthly-dues/createjev',
                          type   : 'POST',
                          data   : {selection:selection,transaction_date:transaction_date},
                      });
               } 
            }
        });
    });
</script>