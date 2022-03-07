<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use app\models\PpeDepreciation;

/* @var $this yii\web\View */
/* @var $model app\models\Ppe */

$this->title = $model->uacs0->object_code;
$this->params['breadcrumbs'][] = ['label' => 'PPEs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    th{
        text-align: center;
    }
</style>
<div class="row">
    <div class="col-md-7">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute'=>'date_acquired',
                    'value'=>function($model){return date('F j, Y',strtotime($model->date_acquired));}
                ],
                'uacs0.grouping',
                'uacs0.object_code',
                'particular',
                //'quantity',
                //'unit',
                [
                    'attribute'=>'unit_cost',
                    'label'=>'Unit/Total Cost',
                    'value'=>function($model){return $model->quantity.' * '.number_format($model->unit_cost,2).' = '.number_format($model->unit_cost*$model->quantity,2) ;}
                ],
                'eul',
                [
                    'label'=>'Annual Depreciation Cost',
                    'value'=>function($model){return number_format(($model->unit_cost*$model->quantity)/$model->eul,2);}
                ],
                [
                    'label'=>'Total Accumulated Depreciation',
                    'value'=>function($model){return number_format(PpeDepreciation::find()->where(['ppeID'=>$model->id])->sum('amount'),2);}
                ],
                [
                    'label'=>'Carrying Value',
                    'value'=>function($model){return number_format(($model->unit_cost*$model->quantity)-(PpeDepreciation::find()->where(['ppeID'=>$model->id])->sum('amount')),2);}
                ],
                [
                    'attribute'=>'warranty_period',
                    'value'=>function($model){return date('F j, Y',strtotime($model->warranty_period));}
                ],
                'receipt_number',
            ],
        ]) ?>
        <hr>
        <h2>Depreciations<small><span class="pull-right"><?= Html::button('<i class="glyphicon glyphicon-plus"></i> Add Depreciation', ['value' => Url::to(['ppe-depreciation/create','ppeID'=>$model->id]), 'title' => 'Add New Depreciation', 'class' => 'showModal btn btn-success btn-xs']) ?></h2><br>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'jev1',
                //'ap_id',
                //'dvNumber.dv_number',
                [
                    'label'=>'Inclusive Dates',
                    'value'=>function($model){
                        return date('F j, Y',strtotime($model->date_from)).' - '.date('F j, Y',strtotime($model->date_to));
                    }
                ],
                [
                    'label'=>'Amount',
                    'value'=>function($model){
                        return number_format($model->amount,2);
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
                            return Yii::$app->user->can('treasurer-only') ? Html::a('<span class="glyphicon glyphicon-trash"></span>', ['ppe-depreciation/delete','id'=>$model->id],[
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                            ]) : '';
                        },
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>
<script type="text/javascript">
    $(function(){
          $(document).on('click', '.showModal', function(){
                $('#genericmodal').modal('show')
                        .find('#modal-body')
                        .load($(this).attr('value'));
                document.getElementById('modal-title').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        });
            
    });
</script>