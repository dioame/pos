<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Members;
use app\models\OrArTracking;

/* @var $this yii\web\View */
/* @var $model app\models\Capitals */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
    <center><h3>Capital Contribution</h3><hr></center>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_posted')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-m-d',
            'todayHighlight' => true
        ],
    ]) ?>
    
    <?php
        echo $form->field($model, 'membersId')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Members::find()->all(), 'id', 'fulllist'),
            'options' => ['placeholder' => 'Select a member ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'arNo')->textInput(['value'=>OrArTracking::getNewor()]) ?>

    <?= $form->field($model, 'type')->dropDownList([ 'cash' => 'Cash']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
