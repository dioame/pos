<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Employees;
use app\models\AuthItem;

/* @var $this yii\web\View */
/* @var $model app\models\AuthAssignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-assignment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        echo $form->field($model, 'user_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Employees::find()->all(), 'id', 'fulllist'),
            'options' => ['placeholder' => 'Select a user ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?php
        echo $form->field($model, 'item_name')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(AuthItem::find()->all(), 'name', 'name'),
            'options' => ['placeholder' => 'Select item ...'],
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
