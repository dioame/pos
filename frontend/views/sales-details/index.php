<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalesDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sales Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-details-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            //'id',
            [
             'attribute'=>'date',
             'value' => function($model){
                    return ucwords(strtolower($model->sales->transaction_date));
                }
            ],
            [
             'attribute'=>'lastname',
             'value' => function($model){
                    return ucwords(strtolower($model->sales->customer->lastname));
                }
            ],
            [
             'attribute'=>'firstname',
             'value' => function($model){
                    return ucwords(strtolower($model->sales->customer->firstname));
                }
            ],
            [
             'attribute'=>'product_id',
             'value' => function($model){
                    return $model->product->product_name;
                }
            ],
            //'product_id',
            'quantity',
            [
             'attribute'=>'product_price',
             'format'=>['decimal',2],
             'value' => function($model){
                    return $model->product_price;
                }
            ],
            [
             'attribute'=>'sub_total',
             'format'=>['decimal',2],
             'value' => function($model){
                    return $model->sub_total;
                },
             'pageSummary'=>true
            ],
            [
             'attribute'=>'paid',
             'filter'=>[0=>'utang',1=>'paid'],
             'value' => function($model){
                    return $model->sales->amount_paid==0?'utang':'paid';
                }
            ],
            'sales_id',
            //'buying_price',
            ['class' => 'kartik\grid\ActionColumn'],

        ],
        'toolbar' =>  [
                ['content'=>
                    ''
                ],
                '{export}',
                '{toggleData}'
            ],
            'pjax' => true,
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
    <?php Pjax::end(); ?>
</div>
