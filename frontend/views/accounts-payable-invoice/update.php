<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AccountsPayableInvoice */

$this->title = 'Update Accounts Payable Invoice: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Accounts Payable Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="accounts-payable-invoice-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
