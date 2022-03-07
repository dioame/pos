<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StockCard */

$this->title = 'Create Stock Card';
$this->params['breadcrumbs'][] = ['label' => 'Stock Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stock-card-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
