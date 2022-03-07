<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevTracking */

$this->title = 'Update Jev Tracking: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jev Trackings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jev-tracking-update">
	
    <?= $this->render('_form2', [
        'model' => $model,
    ]) ?>

</div>
