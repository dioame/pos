<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Products;

/* @var $this yii\web\View */
/* @var $model app\models\InventoryLoss */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class="col-md-offset-3 col-md-6 col-md-offset-3">
	<center><h2><?= Html::encode($this->title) ?></h2><hr></center>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_posted')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-m-d',
            'todayHighlight' => true
        ],
    ]) ?>

    <?php
        echo $form->field($model, 'pID')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Products::find()->all(), 'id', 'product_name'),
            'pluginOptions' => [
                'allowClear' => true
            ]
        ]);

    ?>

    <?= $form->field($model, 'quantity')->textInput(['type'=>'number']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>
