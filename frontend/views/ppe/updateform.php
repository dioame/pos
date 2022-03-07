<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Uacs;

/* @var $this yii\web\View */
/* @var $model app\models\Ppe */
/* @var $form yii\widgets\ActiveForm */
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
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
        <center><h2><?= $this->title ?></h2><hr></center>

        <?= $form->field($model, 'particular')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'warranty_period')->widget(DatePicker::classname(), [
            'type' => DatePicker::TYPE_INPUT,
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-m-d',
                'todayHighlight' => true
            ],
        ]) ?>

        <?= $form->field($model, 'receipt_number')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>