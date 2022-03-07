<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PpeDepreciation */

$this->title = 'Update Ppe Depreciation: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ppe Depreciations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ppe-depreciation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
