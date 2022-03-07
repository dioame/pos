<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Members;

/* @var $this yii\web\View */
/* @var $model app\models\MonthlyDues */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
	<center><h1><?= Html::encode($this->title) ?></h1><hr></center>
    <?php $form = ActiveForm::begin(); ?>

    <?php
        echo $form->field($model, 'mID')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Members::find()->all(), 'id', 'fulllist'),
            'options' => ['placeholder' => 'Select members ...'],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true
            ],
        ]);

    ?>
    <div class="row">
    	<div class="col-md-4">
    		<?= $form->field($model, 'month')->dropDownList([ 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec', ], ['prompt' => '']) ?>
    	</div>
    	<div class="col-md-4">
    		<?= $form->field($model, 'year')->textInput(['maxlength' => true,'type'=>'number']) ?>
    	</div>
    	<div class="col-md-4">
    		<?= $form->field($model, 'amount')->textInput(['maxlength' => true,'type'=>'number','step'=>0.01]) ?>
    	</div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
