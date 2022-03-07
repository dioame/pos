<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PcvTracking */

$this->title = 'Update Pcv Tracking: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pcv Trackings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pcv-tracking-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('updateform', [
        'model' => $model,
    ]) ?>

</div>
