<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\PayrollDividends */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
    <center><h2><?= Html::encode($this->title) ?></h2></center><hr>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_from')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ])->label('Date Posted') ?>

    <?= $form->field($model, 'hourly_rate')->textInput()->label('Amount') ?>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>