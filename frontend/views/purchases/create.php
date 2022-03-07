<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Purchases */

$this->title = 'Record New Purchases';
$this->params['breadcrumbs'][] = ['label' => 'Purchases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchases-create">

    <center><h1><?= Html::encode($this->title) ?></h1></center>
    <hr>

    <?= $this->render('_form', [
        'modelPurchases' => $modelPurchases,
        'modelDetails' => $modelDetails,
    ]) ?>

</div>
