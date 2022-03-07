<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\JevEntries */

$this->title = 'Create Jev Entries';
$this->params['breadcrumbs'][] = ['label' => 'Jev Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-entries-create">
	
    <?= $this->render('_form', [
        'jev' => $jev,
        'type' => $type,
        'model' => $model,
    ]) ?>

</div>
