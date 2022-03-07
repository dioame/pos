<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Officers */

$this->title = 'Create Officers';
$this->params['breadcrumbs'][] = ['label' => 'Officers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="officers-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
