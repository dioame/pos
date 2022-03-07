<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Sales;

/* @var $this yii\web\View */
/* @var $model app\models\ExpensesPaymentPayable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expenses-payment-payable-form">

    <div class="col-md-6">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'transaction_date')->widget(DatePicker::classname(), [
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-m-d',
                'todayHighlight' => true
            ],
        ]) ?>

        <?php
            echo $form->field($model, 'expense_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Sales::find()->andWhere(['>','sales_on_credit',0])->all(), 'id', 'details'),
                'options' => ['placeholder' => 'Select sales details...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);

        ?>

        <?= $form->field($model, 'amount_paid')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            <?= $model->id?Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]):'' ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
