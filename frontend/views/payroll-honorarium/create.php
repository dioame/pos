<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PayrollHonorarium */

$this->title = 'Create Payroll Honorarium';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Honoraria', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'deductionModel' => $deductionModel,
    ]) ?>

</div>
