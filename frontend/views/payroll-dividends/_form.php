<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Members;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollDividends */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    .summary{
        font-size: 2em;width: 100%;
        background-color: #fffbe4;
        border:none;
        padding:10px;
        text-align: right;
    }
</style>
<div class="row">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-offset-3 col-md-6 col-md-offset-3">
        <center><h1><?= Html::encode($this->title) ?></h1><hr></center>
        <div class="col-md-6">
            <?= $form->field($model, 'date_from')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ])->label('Date Posted') ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'hourly_rate')->textInput(['type'=>'number','value'=>0])->label('Dividends Rate (per member)') ?>
        </div>
        <div class="col-md-12">
            <?php
                echo $form->field($model, 'emp_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Members::find()->all(), 'id', 'fulllist'),
                    'options' => ['placeholder' => 'Select members ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true
                    ],
                ])->label('Members');

            ?>
            <hr>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>