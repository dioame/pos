<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\OfficersPositions;
use app\models\Members;

/* @var $this yii\web\View */
/* @var $model app\models\Officers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
	<center><h3>Add New Officer</h3></center><hr>
    <?php $form = ActiveForm::begin(); ?>

    <?php
        echo $form->field($model, 'pID')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(OfficersPositions::find()->all(), 'id', 'title'),
            'options' => ['placeholder' => 'Select position ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?php
        echo $form->field($model, 'mID')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Members::find()->all(), 'id', 'fulllist'),
            'options' => ['placeholder' => 'Select member ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?= $form->field($model, 'start')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'options'=>['value'=>date('Y-m-d'),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]) ?>

    <?= $form->field($model, 'end')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'options'=>['value'=>date('Y-m-d'),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
