<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Expenses */

$this->title = 'Create Disbursement Voucher';
$this->params['breadcrumbs'][] = ['label' => 'Disbursement Journal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disbursement voucher">
    <center><h1><?= Html::encode($this->title) ?></h1><hr></center>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
