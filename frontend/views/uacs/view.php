<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Uacs */

$this->title = $model->uacs;
$this->params['breadcrumbs'][] = ['label' => 'Uacs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uacs-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->uacs], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->uacs], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'classification',
            'sub_class',
            'grouping',
            'object_code',
            'uacs',
            'status',
            'isEnabled',
        ],
    ]) ?>

</div>
