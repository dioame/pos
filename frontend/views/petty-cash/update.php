<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PettyCash */

$this->title = 'Update Petty Cash: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Petty Cashes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="petty-cash-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
