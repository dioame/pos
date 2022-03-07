<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StockCard */

$this->title = 'Update Stock Card: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stock Cards', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stock-card-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'hoursfrom'=>date('h',strtotime($model->date)),
        'hoursto'=>date('h',strtotime($model->finished)),
        'minutesfrom'=>date('h',strtotime($model->date)),
        'minutesto'=>date('h',strtotime($model->finished)),
        'tempdate'=>date('Y-m-d',strtotime($model->date)),
        'tempfinished'=>date('Y-m-d',strtotime($model->finished))
    ]) ?>

</div>
