<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Officers */

$this->title = 'Update Officers: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Officers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="officers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
