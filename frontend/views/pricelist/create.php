<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pricelist */

$this->title = 'Add New Price';
$this->params['breadcrumbs'][] = ['label' => 'Pricelists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pricelist-create">

    

    <?= $this->render('_form', [
        'model' => $model,
        'pID' => $pID,
    ]) ?>

</div>
