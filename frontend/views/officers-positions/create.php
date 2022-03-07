<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OfficersPositions */

$this->title = 'Create Officers Positions';
$this->params['breadcrumbs'][] = ['label' => 'Officers Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="officers-positions-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
