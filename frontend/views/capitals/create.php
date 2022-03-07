<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Capitals */

$this->title = 'Create Capitals';
$this->params['breadcrumbs'][] = ['label' => 'Capitals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capitals-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
