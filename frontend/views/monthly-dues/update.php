<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MonthlyDues */

$this->title = 'Update Monthly Dues: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Monthly Dues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="monthly-dues-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
