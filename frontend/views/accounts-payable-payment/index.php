<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountsPayablePaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts Payable Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-payable-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Accounts Payable Payment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'ap_id',
            'dv_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
