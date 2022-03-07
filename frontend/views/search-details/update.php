<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SalesDetails */

$this->title = 'Update Sales Details: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Sales Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sales-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
