<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MonthlyDues */

$this->title = 'Record Monthly Dues';
$this->params['breadcrumbs'][] = ['label' => 'Monthly Dues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="monthly-dues-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
