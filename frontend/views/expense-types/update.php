<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ExpenseTypes */

$this->title = 'Update Expense Types: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expense Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expense-types-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
