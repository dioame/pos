<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Uacs;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\ProductCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
	<center><h1><?= Html::encode($this->title) ?></h1></center><hr>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?php
        echo $form->field($model, 'salesAccount')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Uacs::find()->where(['isEnabled'=>'1'])->all(), 'uacs', 'object_code'),
            'options' => ['placeholder' => 'Select account code...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?php
        echo $form->field($model, 'purchaseAccount')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Uacs::find()->where(['isEnabled'=>'1'])->all(), 'uacs', 'object_code'),
            'options' => ['placeholder' => 'Select account code...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
