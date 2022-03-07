<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\JevTracking;

/* @var $this yii\web\View */
/* @var $model app\models\JevTracking */
/* @var $form yii\widgets\ActiveForm */

/*$lastjev = JevTracking::find()->orderBy('id DESC')->one();
if($lastjev){
    $pieces = explode("-",$lastjev->jev);
    $lastjev = (int)$pieces[2]+1;
}else{
    $lastjev = 1;
}*/
?>

<style type="text/css">
    a{
        cursor: pointer;

    }
    a:hover{
    	text-decoration: none;
    }
    .center{
        text-align: center;
    }
    .rightni{
        text-align: right;
    }
    th{
        text-align: center;
        padding: 5px;
        border: 1px solid black;
    }
    td{
        padding: 5px;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }
</style>

<div class="row">
	<div class="col-md-12">
		<center><h3>Create New Journal Entry</h3><hr></center>
	</div>
<div class="col-md-offset-3 col-md-6 col-md-offset-3">

		<?php $form = ActiveForm::begin(); ?>

	<div class="col-md-6">

		<?= $form->field($model, 'jev')->textInput([
			'maxlength' => true,
			'value'=>($model->jev?$model->jev:JevTracking::tempJev()),
			'readOnly'=>true
			]) ?>

	</div>
	<div class="col-md-6">

		<?= $form->field($model, 'date_posted')->widget(DatePicker::classname(), [
		    'options'=>['value'=>$model->date_posted?$model->date_posted:date('Y-m-d'),],
		    'pluginOptions' => [
		        'autoclose'=>true,
		        'format' => 'yyyy-m-d',
		        'todayHighlight' => true
		    ],
		]) ?>

	</div>
	<div class="col-md-8">

		<?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

	</div>
	<div class="col-md-4">
		<?= $form->field($model, 'isClosingEntry')->dropDownList([ 'no' => 'No', 'yes' => 'Yes', ]) ?>

	</div>
	<div class="col-md-12">

		<div class="form-group"><br>
		    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
		</div>

	</div>

		<?php ActiveForm::end(); ?>

	<div class="col-md-12" <?= Yii::$app->controller->action->id=='create'?'style="visibility: hidden;':'' ?> >
		<table width="100%" border="0" style="border-bottom: 1px solid black;">
			<tr>
				<th colspan="2" width="50%">DEBIT<a class="showModal" title="Add Debit Entry" value="<?= Url::to(['jev-entries/create','jev'=>$model->jev,'type'=>'debit','id'=>$model->id]) ?>"><small class="label label-primary pull-right">Add New</small></a></th>
				<th colspan="2" width="50%">CREDIT<a class="showModal" title="Add Credit Entry" value="<?= Url::to(['jev-entries/create','jev'=>$model->jev,'type'=>'credit','id'=>$model->id]) ?>"><small class="label label-warning pull-right">Add New</small></a></th>
			</tr>
			<?php
				$totaldebit=0;
				$totalcredit=0;
				$credits = [];
				foreach ($model->jevEntries as $key) {
					if($key->type=='debit'){
						echo '<tr><td>'.
							Html::a('<small class="label label-danger">-</small> ', ['jev-entries/delete', 'id' => $key->id], [
							            'class' => '',
							            'data' => [
							                'confirm' => 'Are you sure you want to delete this item?',
							                'method' => 'post',
							            ],
							        ])
						.$key->accountingCode->object_code.'</td><td class="rightni">'.number_format($key->amount,2).'</td><td></td><td></td></tr>';
						$totaldebit+=$key->amount;
					}else{
						array_push($credits,['object_code'=>$key->accountingCode->object_code,'amount'=>$key->amount,'id'=>$key->id]);
						$totalcredit+=$key->amount;
					}
				}
				foreach ($credits as $credits) {
					echo '<tr><td></td><td></td><td>'.

						Html::a('<small class="label label-danger">-</small> ', ['jev-entries/delete', 'id' => $credits['id']], [
						            'class' => '',
						            'data' => [
						                'confirm' => 'Are you sure you want to delete this item?',
						                'method' => 'post',
						            ],
						        ])

						.$credits['object_code'].'</td><td class="rightni">'.number_format($credits['amount'],2).'</td></tr>';
				}
			
			?>
			<tr>
				<td></td>
				<td class="rightni">
					<hr style="border-color:black;padding: 0px;margin: 0px;">
					<?= number_format($totaldebit,2) ?>
					<hr style="border-color:black;padding: 0px;margin: 0px;">
					<hr style="border-color:black;padding: 0px;margin-top: 4px;">
				</td>
				<td></td>
				<td class="rightni">
					<hr style="border-color:black;padding: 0px;margin: 0px;">
					<?= number_format($totalcredit,2) ?>
					<hr style="border-color:black;padding: 0px;margin: 0px;">
					<hr style="border-color:black;padding: 0px;margin-top: 4px;">
				</td>
			</tr>
		</table>
	</div>
</div>
</div>
<script type="text/javascript">
    $(function(){
          $(document).on('click', '.showModal', function(){
                $('#genericmodal').modal('show')
                        .find('#modal-body')
                        .load($(this).attr('value'));
                document.getElementById('modal-title').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
                setTimeout( function() { 
                  $('#jeventries-accounting_code').select2("open"); // Change the value or make some change to the internal state
                  //$('#jeventries-accounting_code').trigger('change.select2'); // Notify only Select2 of changes
                }, 500 );
        });
            
    });
</script>
