<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Pricelist */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
	<center><h1><?= Html::encode($this->title) ?></h1></center><hr>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_adjusted')->widget(DatePicker::classname(), [
		    'options'=>['value'=>$model->date_adjusted?$model->date_adjusted:date('Y-m-d'),],
		    'pluginOptions' => [
		        'autoclose'=>true,
		        'format' => 'yyyy-m-d',
		        'todayHighlight' => true
		    ],
		]) ?>

    <?= $form->field($model, 'pId')->hiddenInput(['value'=>$pID])->label(false) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
