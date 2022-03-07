<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InventorySearchInventoryLoss */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Inventory Losses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-loss-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'date_posted',
                'value'=>function($model){
                    return date('F j, Y',strtotime($model->date_posted));
                }
            ],
            'product.product_name',
            'quantity',
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visibleButtons'=>[
                    'update'=>function($model,$key,$index){
                        return Yii::$app->user->can('manager-only')?true:false;
                    },
                    'delete'=>function($model,$key,$index){
                        return Yii::$app->user->can('manager-only')?true:false;
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
