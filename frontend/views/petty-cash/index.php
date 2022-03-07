<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PettyCashSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Petty Cashes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="petty-cash-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Petty Cash', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date',
            'type',
            'quantity',
            'unit',
            //'unit_cost',
            //'amount',
            //'amount_paid',
            //'balance',
            //'supplier',
            //'jev',
            //'dv',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
