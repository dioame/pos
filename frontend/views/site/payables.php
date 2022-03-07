<?php
use yii\helpers\Url;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Expenses;

$this->title = 'Payables';
$this->params['breadcrumbs'][] = $this->title;

if(isset($_GET['date_from'])){
	$datefrom = date('Y-m-d',strtotime($_GET['date_from']));
}else{
	$datefrom = '2018-01-01';
}

if(isset($_GET['date_to'])){
	$dateto = date('Y-m-d',strtotime($_GET['date_to']));
}else{
	$dateto = date('Y-m-d');
}

$i=1;


$entries = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>20101010])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->all();

$balance = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>20101010,'type'=>'credit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount')-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>20101010,'type'=>'debit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount');
?>
<style type="text/css">
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
		<form action="" method="GET">
			<div class="form-group">
				<div class="col-md-2">
					<input type="date" name="date_from" class="form-control" value="<?= $datefrom ?>" style="width:100%;" />
				</div>
				<div class="col-md-2">
				<input type="date" name="date_to" class="form-control" value="<?= $dateto ?>" style="width:100%;" />
				</div>
				<div class="col-md-1">
				<button type="submit" class="btn btn-success">Submit</button>
				</div>
				<div class="col-md-1">
				<input type="button" onclick="tableToExcel('exportTable', 'Cash Journal - <?= date("m/d/Y") ?>')" value="Export to Excel" class="btn btn-warning">
				</div>
			</div>
		</form>
		<br><br>
		<span class="pull-right">
			<button type="submit" class="btn btn-primary">Add New Payment</button>
		</span>
		<table id="exportTable" width="100%" border="0" style="border-bottom: 1px solid black;">
			<tr>
				<td class="center" colspan="14" style="border-left: 0px;border-right: 0px;padding: 0px;">
					
						<h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
						<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br><br>
						<h3 style="margin:0px;">Accounts Payable Ledger</h3>
						<br><br>

				</td>
			</tr>

			<tr>
				<th rowspan="2">#</th>
				<th rowspan="2">Year</th>
				<th rowspan="2">Month</th>
				<th rowspan="2">Day</th>
				<th rowspan="2">Supplier ID</th>
				<th rowspan="2">Payee Name</th>
				<th colspan="2">Payable</th>
				<th colspan="2">Payments</th>
				<th rowspan="2">Cumulative Balance</th>
				<th colspan="2">Bookkeeper's Note</th>
			</tr>
			<tr>
				<th>Particulars</th>
				<th>Amount</th>
				<th>Reference</th>
				<th>Amount</th>
				<th>JEV#</th>
				<th>Remarks</th>
			</tr>
			<tr>
				<td><?= $i++ ?></td>
				<td><?= date('Y',strtotime($datefrom)) ?></td>
				<td><?= date('m',strtotime($datefrom)) ?></td>
				<td><?= date('d',strtotime($datefrom)) ?></td>
				<td colspan="2"><i><b>Add previous balance...</b></i></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="rightni"><?= number_format($balance,2) ?></td>
				<td></td>
				<td></td>
			</tr>

		<?php
			foreach ($entries as $key) {
				$model = Expenses::find()->where(['jev'=>$key->jev])->one();
				if($key->type=='credit'){
					$balance+=$key->amount;
				}else{
					$balance-=$key->amount;
				}
				echo '<tr>';
				echo '<td>'.$i++.'</td>';
				echo '<td>'.date('Y',strtotime($key->jev0->date_posted)).'</td>';
				echo '<td>'.date('m',strtotime($key->jev0->date_posted)).'</td>';
				echo '<td>'.date('d',strtotime($key->jev0->date_posted)).'</td>';
				echo '<td>'.($model?$model->supplier0->id:'').'</td>';
				echo '<td>'.($model?'<a id="'.$model->supplier.'" value="'.Url::to(['dv-tracking/update','id'=>$model->supplier]).'" class="showModal" title="Update Payee">'.$model->supplier0->supplier_name.'</a>':'').'</td>';
				echo '<td>'.(JevEntries::find()->where(['jev'=>$key->jev,'type'=>'debit'])->one()->accountingCode->object_code).'</td>';
				echo '<td class="rightni">'.($key->type=='credit'?number_format($key->amount,2):'').'</td>';
				echo '<td>'.($key->type=='debit'?'DV-'.$model->dv:'').'</td>';
				echo '<td class="rightni">'.($key->type=='debit'?number_format($key->amount,2):'').'</td>';
				echo '<td class="rightni">'.number_format($balance,2).'</td>';
				echo '<td>'.$key->jev.'</td>';
				echo '<td></td>';
			}
		?>



		</table>
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
                  jQuery( '#expenses-jev').focus();
                }, 500 );
        });
            
    });
</script>