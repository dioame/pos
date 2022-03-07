<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\AccountsPayablePayment;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountsPayableInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts Payable';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-payable-invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            [
                'attribute'=>'invoice_number',
                'value'=>function($model){
                    return 'AP'.$model->invoice_number;
                }
            ],
            [
                'attribute'=>'invoice_date',
                'value'=>function($model){
                    return date('M j, Y',strtotime($model->invoice_date));
                }
            ],
            [
                'attribute'=>'supplier',
                'value'=>function($model){
                    return $model->supplier0->supplier_name;
                }
            ],
            //'po_number',
            //'type_of_expense',
            [
                'label'=>'Amount Payable',
                'format'=>['decimal',2],
                'value'=>function($model){
                    return $model->amount;
                },
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'attribute'=>'paid',
                'label'=>'Amount Paid',
                'format'=>['decimal',2],
                'value'=>function($model){
                    return AccountsPayablePayment::find()->joinWith('dvNumber')->where(['ap_id'=>$model->id])->sum('amount')+AccountsPayablePayment::find()->joinWith('pcvNumber')->where(['ap_id'=>$model->id])->sum('amount');
                },
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'Balance',
                'format'=>['decimal',2],
                'value'=>function($model){
                    return $model->amount-(AccountsPayablePayment::find()->joinWith('dvNumber')->where(['ap_id'=>$model->id])->sum('amount')+AccountsPayablePayment::find()->joinWith('pcvNumber')->where(['ap_id'=>$model->id])->sum('amount'));
                },
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'attribute'=>'due_date',
                'value'=>function($model){
                    return date('M j, Y',strtotime($model->due_date));
                }
            ],
            'jev',

            ['class' => 'kartik\grid\ActionColumn'],
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
