<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PurchaseDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchase Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-details-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Purchase Details', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'purchase_id',
            'product_id',
            'quantity',
            'unit',
            //'cost',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
