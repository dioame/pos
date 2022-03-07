<?php
use yii\helpers\Url;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Sales;
use app\models\PaymentsReceivable;

$this->title = 'Receivables';
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


$entries = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10301010])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->all();

$balance = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10301010,'type'=>'debit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount')-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10301010,'type'=>'credit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount');
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
		<input type="date" name="date_from" class="" value="<?= $datefrom ?>" />
		<input type="date" name="date_to" class="" value="<?= $dateto ?>" />
		<button type="submit">Submit</button> <input type="button" onclick="tableToExcel('exportTable', 'Cash Journal - <?= date("m/d/Y") ?>')" value="Export to Excel">
		</form>
		<br><br>
		<table id="exportTable" width="100%" border="0" style="border-bottom: 1px solid black;">
			<tr>
				<td class="center" colspan="14" style="border-left: 0px;border-right: 0px;padding: 0px;">
					
						<h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
						<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br><br>
						<h3 style="margin:0px;">Accounts Receivable Ledger</h3>
						<br><br>

				</td>
			</tr>

			<tr>
				<th rowspan="2">#</th>
				<th rowspan="2">Year</th>
				<th rowspan="2">Month</th>
				<th rowspan="2">Day</th>
				<th rowspan="2">SOA#</th>
				<th rowspan="2">Customer ID#</th>
				<th rowspan="2">Customer Name</th>
				<th colspan="2">Collectible</th>
				<th colspan="2">Collections</th>
				<th rowspan="2">Cumulative Balance</th>
				<th colspan="2">Bookkeeper's Note</th>
			</tr>
			<tr>
				<th>Particulars</th>
				<th>Amount Receivable</th>
				<th>Reference</th>
				<th>Amount</th>
				<th>JEV#</th>
				<th>Remarks</th>
			</tr>

		<?php
			foreach ($entries as $key) {
				if($key->type=='debit'){
					$model = Sales::find()->where(['jev'=>$key->jev])->one();
					$balance+=$key->amount;

					echo '<tr>';
					echo '<td>'.$i++.'</td>';
					echo '<td>'.date('Y',strtotime($key->jev0->date_posted)).'</td>';
					echo '<td>'.date('m',strtotime($key->jev0->date_posted)).'</td>';
					echo '<td>'.date('d',strtotime($key->jev0->date_posted)).'</td>';
					echo '<td>'.'SID#'.$model->id.'</td>';
					echo '<td>'.$model->customer->id.'</td>';
					echo '<td>'.$model->customerfullname.'</td>';
					echo '<td>'.$key->jev0->remarks.'</td>';
					echo '<td class="rightni">'.($key->type=='debit'?number_format($key->amount,2):'').'</td>';
					echo '<td>'.($key->type=='credit'?'OR-'.$model->orNo:'').'</td>';
					echo '<td class="rightni">'.($key->type=='credit'?number_format($key->amount,2):'').'</td>';
					echo '<td class="rightni">'.number_format($balance,2).'</td>';
					echo '<td>'.$key->jev.'</td>';
					echo '<td></td>';

				}else{
					$model = PaymentsReceivable::find()->where(['jev'=>$key->jev])->one();
					$balance-=$key->amount;

					echo '<tr>';
					echo '<td>'.$i++.'</td>';
					echo '<td>'.date('Y',strtotime($key->jev0->date_posted)).'</td>';
					echo '<td>'.date('m',strtotime($key->jev0->date_posted)).'</td>';
					echo '<td>'.date('d',strtotime($key->jev0->date_posted)).'</td>';
					echo '<td>'.'SID#'.$model->sales->id.'</td>';
					echo '<td>'.$model->sales->customer->id.'</td>';
					echo '<td>'.$model->sales->customerfullname.'</td>';
					echo '<td>'.$key->jev0->remarks.'</td>';
					echo '<td class="rightni">'.($key->type=='debit'?number_format($key->amount,2):'').'</td>';
					echo '<td>'.($key->type=='credit'?'OR-'.$model->or->tracking:'').'</td>';
					echo '<td class="rightni">'.($key->type=='credit'?number_format($key->amount,2):'').'</td>';
					echo '<td class="rightni">'.number_format($balance,2).'</td>';
					echo '<td>'.$key->jev.'</td>';
					echo '<td></td>';
				}
				
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