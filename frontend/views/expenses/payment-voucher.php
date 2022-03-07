<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Suppliers;
use app\models\DvTracking;
use app\models\Uacs;
use app\models\Employees;

$this->title = 'Payment Voucher';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(); ?>
<style type="text/css">
	.inside{
		padding-left: 10px;
		padding-right: 10px;
		vertical-align: top;
	}
	.select2-selection .select2-selection--single{
		height:20px;
		padding-top: 8px;
		padding-bottom: 8px;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-3">
				<?php
				    echo $form->field($dvmodel, 'payee')->widget(Select2::classname(), [
				        'data' => ArrayHelper::map(Suppliers::find()->all(), 'id', 'supplier_name'),
				        'options' => ['placeholder' => 'Select payee ...'],
				        'pluginOptions' => [
				            'allowClear' => true
				        ],
				    ]);

				?>
			</div>
			<div class="col-md-3">
				<?php
				    echo $form->field($dvmodel, 'credit')->widget(Select2::classname(), [
				        'data' => ArrayHelper::map(Uacs::find()->where(['payment_account'=>'yes'])->all(), 'uacs', 'object_code'),
				        'options' => ['placeholder' => 'Select account type ...'],
				        'pluginOptions' => [
				            'allowClear' => true
				        ],
				    ])->label('From Account');

				?>
			</div>
			<div class="col-md-offset-3 col-md-3">
				<br>
				<button class="btn btn-success pull-right">Submit</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<?= $form->field($dvmodel, 'date_posted')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'options'=>['value'=>date('Y-m-d'),],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-m-d',
                        'todayHighlight' => true
                    ],
                ]) ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($dvmodel, 'dv_number')->textInput([
					'style'=>'text-align:right;',
					'maxlength' => true,
					'value'=>($dvmodel->dv_number?$dvmodel->dv_number:DvTracking::getNewdvnumber())
					]) ?>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-12" id="dynamic-container">
				<table width="100%">
					<tr>
						<th class="inside">Description</th>
						<th class="inside">Account</th>
						<th class="inside" width="8%">Qty</th>
						<th class="inside" width="10%">Unit Price</th>
						<th class="inside" width="8%">Dsct (%)</th>
						<th class="inside" width="8%">Tax (%)</th>
						<th class="inside" width="8%">Tax Amt</th>
						<th class="inside" width="15%">Amount</th>
					</tr>
				</table>
				
				
			</div>
			<div class="col-md-12">
				<button type="button" class="addline btn btn-default btn-xs">Add Line</button>
			</div>
		</div>
	</div>
</div>

<?php ActiveForm::end(); ?>
<script type="text/javascript">
	$(document).ready(function(){
	    $(".addline").click(function(){
	    	var numItems = $('.particularitems').length+1;

	    	//$( "#particulars" ).clone().insertAfter( "#particulars" );

	    $("#dynamic-container").append($('<table width="100%" class="particularitems"><tr><td class="inside"><div class="form-group field-paymentvoucher-description required"><input type="text" id="paymentvoucher-description" class="form-control" name="description[]" maxlength="300" aria-required="true"><div class="help-block"></div></div></td><td width="20%" class="inside"><select id="selector'+numItems+'" class="form-control" ><option>test</option><option>asdasd</option><select/></td><td width="8%" class="inside"><div class="form-group field-paymentvoucher-quantity required"><input type="number" id="paymentvoucher-quantity" class="form-control" name="PaymentVoucher[quantity]" step="0.01" aria-required="true"><div class="help-block"></div></div></td><td width="10%" class="inside"><div class="form-group field-paymentvoucher-unit_price required"><input type="number" id="paymentvoucher-unit_price" class="form-control" name="PaymentVoucher[unit_price]" step="0.01" aria-required="true"><div class="help-block"></div></div></td><td width="8%" class="inside"><div class="form-group field-paymentvoucher-discount"><input type="number" id="paymentvoucher-discount" class="form-control" name="PaymentVoucher[discount]" step="0.01"><div class="help-block"></div></div></td><td width="8%" class="inside"><div class="form-group field-paymentvoucher-tax"><input type="number" id="paymentvoucher-tax" class="form-control" name="PaymentVoucher[tax]" step="0.01"><div class="help-block"></div></div></td><td width="8%" class="inside"><input type="number" step="0.01" name="" class="form-control" readonly=""></td><td width="15%" class="inside"><input type="number" step="0.01" name="" class="form-control" readonly=""></td></tr></table>'));

	   /* $('select')
	             .append($("<option></option>")
	                        .attr("value",'option2')
	                        .text('option2')); */

	    var studentSelect = $('select[data-widget="select2"]');
	    $("#selector"+numItems).select2();
	    studentSelect.val(null).trigger('change');
	    $.ajax({
	        type: 'GET',
	        url: '/pos/frontend/web/uacs/allexpensetypes'
	    }).then(function (data) {
	        // create the option and append to Select2
	        var option = new Option(data.full_name, data.id, true, true);
	        studentSelect.find('option').remove().end().append(option).trigger('change');

	        // manually trigger the `select2:select` event
	        studentSelect.trigger({
	            type: 'select2:select',
	            params: {
	                data: data
	            }
	        });
	    });
	      });

	      // select the target node
	     /* var target = document.getElementById('dynamic-container');

	      if (target) {
	        // create an observer instance
	        var observer = new MutationObserver(function(mutations) {
	          //loop through the detected mutations(added controls)
	          mutations.forEach(function(mutation) {
	          //addedNodes contains all detected new controls
	            if (mutation && mutation.addedNodes) {
	              mutation.addedNodes.forEach(function(elm) {
	              //only apply select2 to select elements
	                if (elm && elm.nodeName === "SELECT") {
	                  $(elm).select2();
	                }
	              });
	            }
	          });
	        }); 
	        
	        // pass in the target node, as well as the observer options
	        observer.observe(target, {
	          childList: true
	        });

	        // later, you can stop observing
	        //observer.disconnect();
	      }*/
	  
	});
</script>