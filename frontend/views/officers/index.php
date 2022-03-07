<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OfficersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Officers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="officers-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute'=>'mID',
                'value'=>function($model){
                    return $model->m->fulllist;
                }
            ],
            [
                'attribute'=>'pID',
                'value'=>function($model){
                    return $model->p->title;
                }
            ],
            'start',
            'end',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                ['content'=>
                    Html::a('Create New Officer', ['create'], ['class' => 'btn btn-success'])
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
            'showPageSummary' => false,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT
            ],
    ]); ?>
</div>
