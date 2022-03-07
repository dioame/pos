<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Employees */

$this->title = 'Create Employees';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employees-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
