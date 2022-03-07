<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Uacs */

$this->title = 'Update Uacs: ' . $model->uacs;
$this->params['breadcrumbs'][] = ['label' => 'Uacs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->uacs, 'url' => ['view', 'id' => $model->uacs]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="uacs-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
