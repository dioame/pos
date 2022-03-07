<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PayrollDividends */

$this->title = 'Create Payroll Dividends';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Dividends', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-dividends-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
