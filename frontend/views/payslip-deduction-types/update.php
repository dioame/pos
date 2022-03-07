<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PayslipDeductionTypes */

$this->title = 'Update Payslip Deduction Types: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payslip Deduction Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payslip-deduction-types-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
