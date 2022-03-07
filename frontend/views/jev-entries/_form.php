<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Uacs;

/* @var $this yii\web\View */
/* @var $model app\models\JevEntries */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-entries-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'jev')->hiddenInput(['value'=>$jev])->label(false) ?>

    <?php
        echo $form->field($model, 'accounting_code')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Uacs::find()->where(['isEnabled'=>'1'])->all(), 'uacs', 'object_code'),
            'options' => ['placeholder' => 'Select account...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?= $form->field($model, 'type')->hiddenInput(['value'=>$type])->label(false) ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
	
</script>
