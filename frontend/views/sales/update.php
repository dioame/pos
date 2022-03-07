<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sales */

$this->title = 'Update Sales: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
