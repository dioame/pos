<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\grid\GridView;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Sales;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales Journal';
$this->params['breadcrumbs'][] = $this->title;


?>
<style type="text/css">
  .center{
    text-align: center;
  }
  .rightni{
    text-align: right;
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
<div class="sales-journal">
  <h1><?= Html::encode($this->title) ?></h1>
    <!-- <?php echo $this->render('_search', ['model' => $searchModel]); ?> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'date_posted',
                'value'=>function($model){
                    return date('F j, Y',strtotime($model->jev0->date_posted));
                }
            ],
            [
                'attribute'=>'reference',
                'value'=>function($model){
                  if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                    return 'SID'.date('Y-m-',strtotime($sales->transaction_date)).$sales->id;
                }
            ],
            [
                'label'=>'Customer Name',
                'value'=>function($model){
                  if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                    return $sales->customerfullname;
                }
            ],
            'jev0.remarks',
            [
                'label'=>'Cash Sales',
                'value'=>function($model){
                  if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                    return $sales->amount_paid;
                },
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'Sales on Credit',
                'value'=>function($model){
                  if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                    return $sales->sales_on_credit;
                },
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'Total Sales',
                'value'=>function($model){
                  if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                    return $sales->amount_paid+$sales->sales_on_credit;
                },
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'JEV',
                'value'=>function($model){
                  return $model->jev;
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{delete}',
                'visibleButtons'=>[
                    'delete'=>function($model,$key,$index){
                        return Yii::$app->user->can('treasurer-only')?true:false;
                    }
                ]
            ],
        ],
        'toolbar' =>  [
                ['content'=>''
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

   