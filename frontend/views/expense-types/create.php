<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExpenseTypes */

$this->title = 'Create Expense Types';
$this->params['breadcrumbs'][] = ['label' => 'Expense Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-types-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
