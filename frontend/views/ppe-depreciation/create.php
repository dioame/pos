<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PpeDepreciation */

$this->title = 'Create Ppe Depreciation';
$this->params['breadcrumbs'][] = ['label' => 'PPE Depreciations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppe-depreciation-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
