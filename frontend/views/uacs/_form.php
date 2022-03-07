<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Uacs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="uacs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'classification')->dropDownList(['Asset'=>'Asset','Expenses'=>'Expenses','Liabilities'=>'Liabilities','Equity'=>'Equity','Income'=>'Income'],['prompt'=>'']); ?>

    <?= $form->field($model, 'sub_class')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'grouping')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'object_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uacs')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(['Active'=>'Active']); ?>

    <?= $form->field($model, 'payment_account')->dropDownList(['yes'=>'Yes','no'=>'No']); ?>

    <?= $form->field($model, 'isEnabled')->dropDownList(['1'=>'Yes','0'=>'No'],['value'=>0]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
