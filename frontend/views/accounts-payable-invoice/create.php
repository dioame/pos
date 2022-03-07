<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AccountsPayableInvoice */

$this->title = 'Create Accounts Payable';
$this->params['breadcrumbs'][] = ['label' => 'Accounts Payable', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-payable-invoice-create">

    <center><h1><?= Html::encode($this->title) ?></h1></center>
    <hr>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
