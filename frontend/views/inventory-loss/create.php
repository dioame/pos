<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\InventoryLoss */

$this->title = 'Record Inventory Loss';
$this->params['breadcrumbs'][] = ['label' => 'Inventory Losses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-loss-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
