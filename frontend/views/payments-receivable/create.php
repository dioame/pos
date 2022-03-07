<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PaymentsReceivable */

$this->title = 'Collection of Receivables';
$this->params['breadcrumbs'][] = ['label' => 'Payments Receivables', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payments-receivable-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
