<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExpensesPaymentPayable */

$this->title = 'Create Expenses Payment Payable';
$this->params['breadcrumbs'][] = ['label' => 'Expenses Payment Payables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expenses-payment-payable-create">

    <?= $this->render('_form', [
    	//'expensesModel' => $expensesModel,
        'model' => $model,
    ]) ?>

</div>
