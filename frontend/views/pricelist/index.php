<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PricelistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pricelists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricelist-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'date_adjusted',
            [
                'attribute'=>'pId',
                'value'=>function($model){
                    return $model->p->product_name;
                }
            ],
            [
                'attribute'=>'price',
                'value'=>function($model){
                    return $model->price;
                },
                'format'=>['decimal',2]
            ],

            //['class' => 'yii\grid\ActionColumn'],
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
