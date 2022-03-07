<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OfficersPositionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Officers Positions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="officers-positions-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'title',
            [
                'attribute'=>'honorarium',
                'format'=>['decimal',2]
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                    ['content'=>
                        Html::a('Create Officers Positions', ['create'], ['class' => 'btn btn-success'])
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
