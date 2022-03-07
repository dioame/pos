<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductCategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Product Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-categories-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'category',
            [
                'attribute'=>'salesAccount',
                'value'=>function($model){
                    return $model->salesAccount0->object_code;
                }
            ],
            [
                'attribute'=>'purchaseAccount',
                'value'=>function($model){
                    return $model->purchaseAccount0->object_code;
                }
            ],

            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                ['content'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Create',['create'],[ 'title' => 'Add New Category', 'class' => 'showModal btn btn-success','style'=>'color:white;'])
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
