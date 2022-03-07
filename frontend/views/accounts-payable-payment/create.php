<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AccountsPayablePayment */

$this->title = 'Accounts Payable Payment (Cash on Hand / Cash in Bank)';
$this->params['breadcrumbs'][] = ['label' => 'Accounts Payable Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accounts-payable-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
        'dvmodel' => $dvmodel,
    ]) ?>

</div>
