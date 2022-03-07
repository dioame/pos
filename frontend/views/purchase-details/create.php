<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PurchaseDetails */

$this->title = 'Create Purchase Details';
$this->params['breadcrumbs'][] = ['label' => 'Purchase Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
