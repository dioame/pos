<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Payroll */

$this->title = 'Update Payroll: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payroll-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
