<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Uacs */

$this->title = 'Create Uacs';
$this->params['breadcrumbs'][] = ['label' => 'Uacs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uacs-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
