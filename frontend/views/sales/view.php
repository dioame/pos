<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\PaymentsReceivable;

/* @var $this yii\web\View */
/* @var $model app\models\Sales */

$this->title = 'SID'.date('Y-',strtotime($model->transaction_date)).$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Accounts Receivable', 'url' => ['sales/receivables']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
<div class="col-md-6">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'transaction_date',
            [
                'label'=>'Sales ID',
                'value'=>function($model){
                    return date('Y-',strtotime($model->transaction_date)).$model->id;
                }
            ],
            [
                'label'=>'Sales Details',
                'value'=>function($model){
                    return $model->jev0->remarks;
                }
            ],
            [
                'label'=>'Sold To',
                'value'=>function($model){
                    return $model->customer->names;
                }
            ],
            [
                'label'=>'Total Amount Due',
                'value'=>function($model){
                    return $model->total;
                },
                'format'=>['decimal',2]
            ],
            [
                'label'=>'Total Amount Paid in Cash',
                'value'=>function($model){
                    return number_format($model->amount_paid,2).' / OR#'.$model->or->tracking;
                },
                'format'=>'raw'
            ],
            [
                'label'=>'Total Amount Paid',
                'value'=>function($model){
                    $otherpayments = PaymentsReceivable::find()->where(['sales_id'=>$model->id])->sum('amount_paid');
                    return $model->amount_paid+$otherpayments;
                },
                'format'=>['decimal',2]
            ],
            [
                'label'=>'Balance',
                'value'=>function($model){
                    $otherpayments = PaymentsReceivable::find()->where(['sales_id'=>$model->id])->sum('amount_paid');
                    return $model->total-($model->amount_paid+$otherpayments);
                },
                'format'=>['decimal',2]
            ],
        ],
    ]) ?>
    <hr>
    <h2>Accounts Receivable Payments</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'ap_id',
            [
                'label'=>'OR#',
                'value'=>function($model){
                    return 'OR#'.$model->or->tracking;
                    
                }
            ],
            [
                'label'=>'JEV',
                'value'=>function($model){
                    return $model->jev;
                    
                }
            ],
            [
                'label'=>'Amount',
                'value'=>function($model){
                    return number_format($model->amount_paid,2);
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{delete}',
                'visibleButtons'=>[
                    'delete'=>function($model,$key,$index){
                        return (Yii::$app->user->can('treasurer-only')?true:false);
                    }
                ],
                'buttons'=>[
                    'delete' => function ($url, $model, $key) {
                        return Yii::$app->user->can('treasurer-only') ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['payments-receivable/delete','id'=>$model->id],[
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this transaction?',
                                    'method' => 'post',
                                ],
                        ]) : '';
                    }
                ]
            ],
        ],
    ]); ?>
</div>
</div>
