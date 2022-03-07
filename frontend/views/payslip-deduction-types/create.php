<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PayslipDeductionTypes */

$this->title = 'Create Payslip Deduction Types';
$this->params['breadcrumbs'][] = ['label' => 'Payslip Deduction Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payslip-deduction-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
