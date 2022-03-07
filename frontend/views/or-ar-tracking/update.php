<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OrArTracking */

$this->title = 'Update Or Ar Tracking: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Or Ar Trackings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="or-ar-tracking-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
