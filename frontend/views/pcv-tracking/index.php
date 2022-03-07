<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PcvTrackingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Petty Cash Vouchers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pcv-tracking-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

        //'id',
        'date_posted',
        'pcv_number',
        [
            'attribute'=>'payee',
            'value'=>function($model){
                if($model->payee)
                return $model->payee0->supplier_name;
            },
        ],
        'particular',
        [
            'attribute'=>'amount',
            'format'=>['decimal',2]
        ],
        //'type',
        //'debit',
        //'credit',
        //'prepared_by',
        //'requested_by',
        //'approved_by',
        //'received_by',
        //'jev',
        [
            'label'=>'JEV',
            'value'=>function($model){
                return $model->jev;
            },
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            /*'template' => '{view}{delete}',
            'visibleButtons'=>[
                'delete'=>function($model,$key,$index){
                    return Yii::$app->user->can('treasurer-only')?true:false;
                }
            ]*/
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
            'showPageSummary' => false,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT
            ],
    ]); ?>
</div>
