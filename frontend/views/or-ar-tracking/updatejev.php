<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\OrArTracking */

$this->title = 'Create Or Ar Tracking';
$this->params['breadcrumbs'][] = ['label' => 'Or Ar Trackings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="or-ar-tracking-create">

    <?= $this->render('_form2', [
        'model' => $model,
    ]) ?>

</div>
