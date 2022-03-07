<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ppe */

$this->title = 'Update Ppe: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Property Plant & Equipments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ppe-update">

    <?= $this->render('updateform', [
        'model' => $model,
    ]) ?>

</div>
