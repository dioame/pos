<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Ppe */

$this->title = 'Create New Property, Plant and Equipment';
$this->params['breadcrumbs'][] = ['label' => 'PPEs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppe-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
