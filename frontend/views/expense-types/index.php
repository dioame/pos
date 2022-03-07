<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpenseTypesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Expense Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-types-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Expense Types', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'amount',
            'recommended_supplier',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
