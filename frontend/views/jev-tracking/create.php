<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JevTracking */

$this->title = 'Create Jev Tracking';
$this->params['breadcrumbs'][] = ['label' => 'Jev Trackings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-tracking-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
