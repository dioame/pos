<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\AccountsPayablePayment;

/* @var $this yii\web\View */
/* @var $model app\models\AccountsPayableInvoice */

$this->title = 'AP'.$model->invoice_number.'-'.$model->typeOfExpense->object_code;
$this->params['breadcrumbs'][] = ['label' => 'Accounts Payable', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <div class="col-md-6">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute'=>'invoice_number',
                    'value'=>function($model){return 'AP'.$model->invoice_number;}
                ],
                'po_number',
                'supplier0.supplier_name',
                [
                    'attribute'=>'invoice_date',
                    'value'=>function($model){return date('F j, Y',strtotime($model->invoice_date));}
                ],
                [
                    'attribute'=>'due_date',
                    'value'=>function($model){return date('F j, Y',strtotime($model->due_date));}
                ],
                'typeOfExpense.object_code',
                [
                    'attribute'=>'amount',
                    'value'=>function($model){return number_format($model->amount,2);}
                ],
                'jev',
            ],
        ]) ?>
        <hr>
        <h2>Payments</h2>
        <br>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                //'id',
                //'ap_id',
                [
                    'label'=>'DV/PCV',
                    'value'=>function($model){
                        if($model->dvNumber){
                            return $model->dv_number;
                        }else{
                            return $model->pcv_number;
                        }
                        
                    }
                ],
                [
                    'label'=>'JEV',
                    'value'=>function($model){
                        if($model->dvNumber){
                            return $model->dvNumber->jev;
                        }else{
                            return $model->pcvNumber->jev;
                        }
                        
                    }
                ],
                [
                    'label'=>'Amount',
                    'value'=>function($model){
                        if($model->dvNumber){
                            return number_format($model->dvNumber->amount,2);
                        }else{
                            return number_format($model->pcvNumber->amount,2);
                        }
                    }
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{view} {delete}',
                    'visibleButtons'=>[
                        'delete'=>function($model,$key,$index){
                            return (Yii::$app->user->can('treasurer-only')?true:false);
                        }
                    ],
                    'buttons'=>[
                        'delete' => function ($url, $model, $key) {
                            return Yii::$app->user->can('treasurer-only') ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['ppe-depreciation/delete','id'=>$model->id],[
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                            ]) : '';
                        },
                        'view' => function ($url, $model, $key) {
                            return Yii::$app->user->can('treasurer-only') ? Html::a('<span class="glyphicon glyphicon-eye-open"></span>', [($model->dvNumber?'expenses/view?id='.$model->dvNumber->id:'pcv-tracking/view?id='.$model->pcvNumber->id)]) : '';
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
    <div class="col-md-6">
        <br>
        <label class="form-label">Total Amount Payable:</label>
        <input type="text" step="0.01" class="form-control" value="<?= number_format($model->amount,2) ?>" style="font-size: 3em;height: 100%;text-align: right;" readonly>
        <br>
        <label class="form-label">Total Amount Paid:</label>
        <input type="text" step="0.01" class="form-control" value="<?= number_format(AccountsPayablePayment::find()->joinWith('dvNumber')->where(['ap_id'=>$model->id])->sum('amount')+AccountsPayablePayment::find()->joinWith('pcvNumber')->where(['ap_id'=>$model->id])->sum('amount'),2) ?>" style="font-size: 3em;height: 100%;text-align: right;" readonly>
        <br>
        <label class="form-label">Balance:</label>
        <input type="text" step="0.01" class="form-control" value="<?= number_format(($model->amount-(AccountsPayablePayment::find()->joinWith('dvNumber')->where(['ap_id'=>$model->id])->sum('amount')+AccountsPayablePayment::find()->joinWith('pcvNumber')->where(['ap_id'=>$model->id])->sum('amount'))),2) ?>" style="font-size: 3em;height: 100%;text-align: right;" readonly>
    </div>
</div>
