<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ExpensesPaymentPayable */

$this->title = 'Update Expenses Payment Payable: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Expenses Payment Payables', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expenses-payment-payable-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
