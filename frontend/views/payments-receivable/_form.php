<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Sales;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentsReceivable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-4 col-md-4 col-md-offset-4">
    <center><h2><?= Html::encode($this->title) ?></h2><hr></center>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'transaction_date')->widget(DatePicker::classname(), [
        'options'=>['value'=>date('Y-m-d'),],
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-m-d',
            'todayHighlight' => true
        ],
    ]) ?>

    <?php
        $balances = Yii::$app->getDb()->createCommand("SELECT id, sales_on_credit, CONCAT('SID#',id,' - ',(SELECT CONCAT(firstname,' ',lastname) FROM customers WHERE customers.id=sales.customer_id),' with a remaining balance of ',(sales_on_credit - ifnull((SELECT sum(a.amount_paid) FROM payments_receivable A JOIN sales B ON A.sales_id=B.id WHERE a.sales_id=b.id GROUP BY a.sales_id),0))) AS 'remarks' FROM sales having (sales_on_credit - ifnull((SELECT sum(a.amount_paid) FROM payments_receivable A JOIN sales B ON A.sales_id=B.id WHERE a.sales_id=b.id GROUP BY a.sales_id),0))>0")->queryAll();
        echo $form->field($model, 'sales_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($balances, 'id', 'remarks'),
            'options' => ['placeholder' => 'Select Receivable...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <?= $form->field($model, 'amount_paid')->textInput(['type'=>'number','step'=>0.01])->label('Amount Collected') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
