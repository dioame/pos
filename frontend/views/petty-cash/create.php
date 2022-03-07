<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PettyCash */

$this->title = 'Petty Cash Disbursement';
$this->params['breadcrumbs'][] = ['label' => 'Petty Cashes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="petty-cash-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
