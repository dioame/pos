<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DvTrackingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'DV Tracking';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-tracking-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'dv_number',
            'date_posted',
            'amount',
            'payee',
            'type',
            'debit',
            'credit',
            'prepared_by',
            'requested_by',
            'approved_by',
            'received_by',

            ['class' => 'kartik\grid\ActionColumn'],
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
