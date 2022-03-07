<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollHonorarium */

$this->title = 'Update Payroll Honorarium: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Honoraria', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payroll-honorarium-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
