<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\AccountsPayableInvoice;
use app\models\Uacs;

$this->title = 'Accounts Payable Payment (Petty Cash)';
$this->params['breadcrumbs'][] = ['label' => 'Accounts Payable Payments', 'url' => ['index']];

/* @var $this yii\web\View */
/* @var $model app\models\AccountsPayablePayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-offset-3 col-md-6 col-md-offset-3">
        <center><h3><?= $this->title ?></h3></center><hr>
    	<div class="row">
    		<div class="col-md-12">
    		    <?= $form->field($dvmodel, 'date_posted')->widget(DatePicker::classname(), [
    		        'options'=>['value'=>($dvmodel->date_posted?$dvmodel->date_posted:date('Y-m-d'))],
    		        'pluginOptions' => [
    		            'autoclose'=>true,
    		            'format' => 'yyyy-m-d',
    		            'todayHighlight' => true
    		        ],
    		    ]) ?>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-md-8">
                <?php
                    $balances = Yii::$app->getDb()->createCommand("SELECT *,id,amount,CONCAT('AP#',invoice_number,' to ',(SELECT supplier_name FROM suppliers WHERE id=accounts_payable_invoice.supplier),' with a remaining balance of ',(amount - ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN dv_tracking B ON A.dv_number=B.dv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0)-ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN pcv_tracking B ON A.pcv_number=B.pcv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0))) AS 'remarks' FROM accounts_payable_invoice having (amount - ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN dv_tracking B ON A.dv_number=B.dv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0)-ifnull((SELECT sum(b.amount) FROM accounts_payable_payment A JOIN pcv_tracking B ON A.pcv_number=B.pcv_number WHERE a.ap_id=accounts_payable_invoice.id GROUP BY a.ap_id),0))>0")->queryAll();
                    echo $form->field($model, 'ap_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map($balances, 'id', 'remarks'),
                        'options' => ['placeholder' => 'Select Payable...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'pluginEvents' => [
                            "select2:select" => "function()
                            {
                                document.getElementById('pcvtracking-particular').value = 'To pay for the accounts payable '+$(this).select2('data')[0].text;
                                //alert($(this).val());                               
                            }",
                        ]
                    ]);

                ?>
    		</div>
            <div class="col-md-4">	
                <?= $form->field($dvmodel, 'amount')->textInput(['type'=>'number','step'=>0.001,'class'=>'rightni form-control']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($dvmodel, 'particular')->textInput()->label('Remarks') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
    			

    			<div class="form-group">
    			    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    			</div>
    		</div>
    	</div>
    </div>

    

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    
</script>