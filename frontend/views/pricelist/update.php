<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pricelist */

$this->title = 'Update Pricelist: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pricelists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pricelist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
