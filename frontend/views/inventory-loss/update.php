<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InventoryLoss */

$this->title = 'Update Inventory Loss: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inventory Losses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inventory-loss-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
