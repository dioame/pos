<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\SalesDetails;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentsReceivableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments Receivables';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-receivable-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'transaction_date',
                'value'=>function($model){
                    return date('F j, Y',strtotime($model->transaction_date));
                }
            ],
            [
                'label'=>'Sales Details',
                'value'=>function($model){
                    $sd = SalesDetails::find()->where(['sales_id'=>$model->sales_id])->one();
                    return $model->sales->jev0->remarks.' to '.$model->sales->customerfullname.' - '.$model->sales->transaction_date;
                }
            ],
            [
                'attribute'=>'amount_paid',
                'format'=>['decimal',2]
            ],
            'jev',
            //'orNo',

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
                ['content'=>
                    ''
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
