<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollDividends */

$this->title = 'Update Payroll Dividends: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Dividends', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payroll-dividends-update">

    

    <?= $this->render('updateform', [
        'model' => $model,
    ]) ?>

</div>
