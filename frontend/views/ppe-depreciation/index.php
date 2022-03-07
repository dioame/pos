<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PpeDepreciationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ppe Depreciations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppe-depreciation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Ppe Depreciation', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ppeID',
            'date_from',
            'date_to',
            'amount',
            //'date_posted',
            //'jev1',
            //'jev2',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
