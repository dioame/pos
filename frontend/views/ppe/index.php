<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use app\models\PpeDepreciation;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PpeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property, Plant and Equipment';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppe-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',

            
            [
                'attribute'=>'date_acquired',
                'hAlign'=>'center',
                'filter' =>DatePicker::widget([
                    'model'=>$searchModel,
                    'type' => DatePicker::TYPE_INPUT,
                    'attribute'=>'date_acquired',
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]),
                'value'=>function($model){
                    return date('F j, Y',strtotime($model->date_acquired));
                }
            ],
            [
                'attribute'=>'sub_class',
                'hAlign'=>'center',
                'value'=>function($model){
                    return $model->uacs0->sub_class;
                }
            ],
            [
                'attribute'=>'uacs',
                'hAlign'=>'center',
                'value'=>function($model){
                    return $model->uacs0->object_code;
                }
            ],
            'particular',
            [
                'label'=>'Qty',
                'hAlign'=>'center',
                'value'=>function($model){
                    return $model->quantity;
                }
            ],
            [
                'label'=>'Unit',
                'hAlign'=>'center',
                'value'=>function($model){
                    return $model->unit;
                }
            ],
            [
                'label'=>'Unit Cost',
                'hAlign'=>'center',
                'format'=>'decimal',
                'value'=>function($model){
                    return $model->unit_cost;
                }
            ],
            [
                'label'=>'Total Cost',
                'hAlign'=>'center',
                'format'=>'decimal',
                'value'=>function($model){
                    return $model->quantity*$model->unit_cost;
                }
            ],
            [
                'label'=>'Estimated Useful Life (in years)',
                'hAlign'=>'center',
                'value'=>function($model){
                    return $model->eul;
                }
            ],
            [
                'label'=>'Annual Depreciation',
                'hAlign'=>'center',
                'format'=>'decimal',
                'value'=>function($model){
                    return ($model->quantity*$model->unit_cost)/$model->eul;
                }
            ],
            [
                'label'=>'Accumulated Depreciation',
                'hAlign'=>'center',
                'format'=>'decimal',
                'value'=>function($model){
                    $payments = 0;
                    $payments = PpeDepreciation::find()->where(['ppeID'=>$model->id])->sum('amount');
                    return $payments;
                }
            ],
            [
                'label'=>'Carrying Amount',
                'hAlign'=>'center',
                'format'=>'decimal',
                'value'=>function($model){
                    $payments = 0;
                    $payments = PpeDepreciation::find()->where(['ppeID'=>$model->id])->sum('amount');
                    return ($model->quantity*$model->unit_cost)-$payments;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}',
                'visibleButtons'=>[
                    'delete'=>function($model,$key,$index){
                        $depreciations = PpeDepreciation::find()->where(['ppeID'=>$model->id])->all();
                        if(!$depreciations){
                            return Yii::$app->user->can('manager-only')?true:false;
                        }
                    }
                ]
            ],
            //'unit_cost',
            //'date_acquired',
            //'eul',

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
            'showPageSummary' => false,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT
            ],
    ]); ?>
</div>
