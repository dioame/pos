<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Payroll */

$this->title = 'Create Payroll';
$this->params['breadcrumbs'][] = ['label' => 'Payrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'deductionModel' => $deductionModel,
    ]) ?>

</div>
