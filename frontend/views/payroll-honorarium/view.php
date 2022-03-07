<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollHonorarium */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Honoraria', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payroll-honorarium-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'id',
            'type',
            'emp_id',
            'date_from',
            'date_to',
            'number_of_hours',
            'hourly_rate',
            'date_created',
            'dv',
            'jev',
        ],
    ]) ?>

</div>
