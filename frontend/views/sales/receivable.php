<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\grid\GridView;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Sales;
use app\models\PaymentsReceivable;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts Receivable';
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
                    return 'SID'.date('Y-',strtotime($sales->transaction_date)).$sales->id;
                }
            ],
            'jev0.remarks',
            [
                'label'=>'Amount Receivable',
                'value'=>function($model){
                  return $model->amount;
                },
                'hAlign'=>'center',
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'Amount Collected',
                'value'=>function($model){
                  if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                    if($payments = PaymentsReceivable::find()->where(['sales_id'=>$sales->id])->sum('amount_paid'))
                        return $payments;
                },
                'hAlign'=>'center',
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'Balance',
                'value'=>function($model){
                  if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                    if($payments = PaymentsReceivable::find()->where(['sales_id'=>$sales->id])->sum('amount_paid'))
                        return $model->amount-$payments;
                },
                'hAlign'=>'center',
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
                'template' => '{view} {delete}',
                'visibleButtons'=>[
                    'delete'=>function($model,$key,$index){
                        return Yii::$app->user->can('treasurer-only')?true:false;
                    }
                ],
                'buttons'=>[
                    'view' => function ($url, $model, $key) {
                        if($sales = Sales::find()->where(['jev'=>$model->jev])->one())
                        return Yii::$app->user->can('treasurer-only') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['sales/view?id='.$sales->id]) : '';
                    },
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

   