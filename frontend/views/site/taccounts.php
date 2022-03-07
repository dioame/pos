<?php

use app\models\JevEntries;

$this->title = 'T-accounts';
$this->params['breadcrumbs'][] = $this->title;

if(isset($_GET['date_from'])){
	$datefrom = date('Y-m-d',strtotime($_GET['date_from']));
}else{
	$datefrom = date('Y-m-01');
}

if(isset($_GET['date_to'])){
	$dateto = date('Y-m-d',strtotime($_GET['date_to']));
}else{
	$dateto = date('Y-m-t');
}


$entries = JevEntries::find()->joinWith('jev0')->select('accounting_code')->where(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->distinct()->all();

$count = count($entries);
$limit = number_format($count/3);
$i=1;
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
		padding:2px;
	}
	td{
		padding:2px;
		vertical-align: top;
	}
</style>
<div class="row">
<div class="col-md-12">
	<form action="" method="GET">
	<input type="date" name="date_from" class="" value="<?= $datefrom ?>" />
	<input type="date" name="date_to" class="" value="<?= $dateto ?>" />
	<button type="submit">Submit</button> <input type="button" onclick="tableToExcel('exportTable', 'Cash Journal - <?= date("m/d/Y") ?>')" value="Export to Excel">
	</form>
	<br>
	<center><h1>T-accounts</h1></center>
</div>
<?php
	echo '<div class="col-md-4">';
	foreach ($entries as $key) {
		$model = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->accounting_code])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->all();
		$credits = [];
		$totaldebit=0;
		$totalcredit=0;
		echo '<div style="padding-bottom:40px;">';
		echo '<table border="1" width="100%">';
		echo '<tr>';
		echo '<th colspan="4" class="center">'.$key->accountingCode->object_code.'</th>';
		echo '</tr>';
		echo '<tr>';
		echo '<tr><th colspan="2">DEBIT</th><th colspan="2">CREDIT</th></tr>';
		echo '<tr><th width="25%">Date</th><th width="25%">Amount</th><th width="25%">Amount</th><th width="25%">Date</th></tr>';
		echo '<td width="50%" colspan="2">';
		echo '<table width="100%">';
		if($key->accountingCode->classification=='Asset'){

			$balance = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->accounting_code,'type'=>'debit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount')-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->accounting_code,'type'=>'credit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount');

			if($totaldebit>0){
				$totaldebit+=$balance;
				echo '<tr>';
				echo '<td>'.date('m/d/y',strtotime('-1 day',strtotime($datefrom))).'</td>';
				echo '<td class="rightni">'.number_format($balance,2).'</td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '</tr>';
			}

		}
		foreach ($model as $model) {
			if($model->type=='debit'){
				echo '<tr>';
				echo '<td width="50%">'.date('m/d/y',strtotime($model->jev0->date_posted)).'</td>';
				echo '<td class="rightni">'.number_format($model->amount,2).'</td>';
				echo '</tr>';
				$totaldebit+=$model->amount;
			}else{
				array_push($credits,[$model->jev0->date_posted,$model->amount]);
				$totalcredit+=$model->amount;
			}
		}
		echo '</table>';
		echo '</td>';

		echo '<td width="50%" colspan=2>';
		echo '<table width="100%">';
		if($key->accountingCode->classification=='Asset'){

			$balance = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->accounting_code,'type'=>'debit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount')-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->accounting_code,'type'=>'credit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount');

			if($balance<0){
				$balance = $balance*-1;
				$totalcredit+=$balance;
				echo '<tr>';
				echo '<td>'.number_format($balance,2).'</td>';
				echo '<td class="rightni">'.date('m/d/y',strtotime('-1 day',strtotime($datefrom))).'</td>';
				echo '<td></td>';
				echo '<td></td>';
				echo '</tr>';
			}

		}
		if($key->accountingCode->classification=='Liabilities'||$key->accountingCode->classification=='Equity'){

			$balance = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->accounting_code,'type'=>'credit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount')-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->accounting_code,'type'=>'debit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount');

			$totalcredit+=$balance;

			echo '<tr>';
			echo '<td>'.number_format($balance,2).'</td>';
			echo '<td class="rightni">'.date('m/d/y',strtotime('-1 day',strtotime($datefrom))).'</td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '</tr>';
		}
		foreach ($credits as $credits) {
			echo '<tr>';
			echo '<td width="50%">'.number_format($credits[1],2).'</td>';
			echo '<td class="rightni">'.date('m/d/y',strtotime($credits[0])).'</td>';
			echo '</tr>';
		}

		echo '</table>';
		echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td colspan="2" class="rightni">'.($totaldebit>0?number_format($totaldebit,2):'').'</td><td colspan="2">'.($totalcredit>0?number_format($totalcredit,2):'').'</td>';
		echo '</tr>';
		echo '<tr>';
		if($totaldebit>$totalcredit){
			$balance = $totaldebit-$totalcredit;
			echo '<th style="border-right:none;text-align:left;">'.date('m/d/y',strtotime($dateto)).'</th><th class="rightni" style="border-left:none">'.number_format($balance,2).'<hr style="border-color:black;padding: 0px;margin: 0px;"><hr style="border-color:black;padding: 0px;margin-top: 4px;margin-bottom:0px;"></th><td colspan=2></td>';
		}else{
			$balance = $totalcredit-$totaldebit;
			echo '<td colspan=2></td><th style="border-right:none;text-align:left;">'.number_format($balance,2).'<hr style="border-color:black;padding: 0px;margin: 0px;"><hr style="border-color:black;padding: 0px;margin-top: 4px;margin-bottom:0px;"></th><th class="rightni" style="border-left:none">'.date('m/d/y',strtotime($dateto)).'</th>';
		}
		echo '</tr>';
		echo '</table>';
		echo '</div>';
		$i++;
		if($i>$limit){
			echo '</div>';
			echo '<div class="col-md-4">';
			$i=1;
		}
	
	}
	echo '</div>';
?>
</div>