<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\models\JevEntries;
use app\models\Uacs;
use app\models\Settings;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Balance Sheet';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
	$t1 = strtotime($a[0]);
	$t2 = strtotime($b[0]);
	return $t1-$t2;
}

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
$totalasset = 0;
$totalliabilities = 0;
$totalequity = 0;
$totaldep = 0;
$rebeg = 0;

$asset=[];
$liabilities=[];
$equity=[];
$depreciations=[];

$allasset = Uacs::find()->where(['classification'=>'Asset'])->all();

foreach ($allasset as $key) {
	$amount = 0;
	$model = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->uacs,'isClosingEntry'=>'no'])->andWhere(['<=','date(date_posted)',$dateto])->all();
	
	foreach ($model as $value) {
		if($value->type=='debit'){
			$amount += $value->amount;
		}else{
			$amount -= $value->amount;
		}
		
	}
	if($amount>0){
		array_push($asset, ['object_code'=>$key->uacs,'amount'=>$amount]);
	}
}

$allppedep = Uacs::find()->where(['classification'=>'Asset','sub_class'=>'Property, Plant and Equipment','isEnabled'=>1])->all();

foreach ($allppedep as $key) {
	$amount = 0;
	$model = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->uacs,'isClosingEntry'=>'no'])->andWhere(['<=','date(date_posted)',$dateto])->all();
	
	foreach ($model as $value) {
		if($value->type=='debit'){
			$amount -= $value->amount;
		}else{
			$amount += $value->amount;
		}
		
	}
	if($amount>0){
		array_push($depreciations, ['object_code'=>$key->uacs,'amount'=>$amount]);
	}
}


$allliabilities = Uacs::find()->where(['classification'=>'Liabilities','isEnabled'=>1])->all();

//$allliabilities = JevEntries::find()->joinWith('jev0')->joinWith('accountingCode')->where(['classification'=>'Liabilities'])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->groupBy('accounting_code')->all();

foreach ($allliabilities as $key) {
	$amount = 0;
	$model = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->uacs,'isClosingEntry'=>'no'])->andWhere(['<=','date(date_posted)',$dateto])->all();

	foreach ($model as $value) {
		if($value->type=='debit'){
			$amount -= $value->amount;
		}else{
			$amount += $value->amount;
		}
		
	}
	if($amount>0){
		array_push($liabilities, ['object_code'=>$key->uacs,'amount'=>$amount]);
	}
}

//$allequity = JevEntries::find()->joinWith('jev0')->joinWith('accountingCode')->where(['classification'=>'Equity'])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->groupBy('accounting_code')->all();

$allequity = Uacs::find()->where(['classification'=>'Equity','isEnabled'=>1])->all();

foreach ($allequity as $key) {
	$amount = 0;
	$model = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>$key->uacs,'isClosingEntry'=>'no'])->andWhere(['<=','date(date_posted)',$dateto])->all();

	foreach ($model as $value) {
		if($value->type=='debit'){
			$amount -= $value->amount;
		}else{
			$amount += $value->amount;
		}
		
	}
	if($amount>0){
		array_push($equity, ['object_code'=>$key->uacs,'amount'=>$amount]);
	}
	
}

$previoussales = 0;
$previousexpenses = 0;
$previouscre = 0;

$previoussales = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>40202160,'type'=>'credit','isClosingEntry'=>'no'])->andWhere(['<=','date(date_posted)',$dateto])->sum('amount');
$previousexpenses = JevEntries::find()->joinWith('jev0')->joinWith('accountingCode')->where(['AND',['classification'=>'Expenses'],['type'=>'debit','isClosingEntry'=>'no']])->andWhere(['<=','date(date_posted)',$dateto])->sum('amount');
$previouscre = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>20000001,'type'=>'credit','isClosingEntry'=>'no'])->andWhere(['<=','date(date_posted)',$dateto])->sum('amount');
$rebeg = $previoussales-$previousexpenses-$previouscre;

?>
<style type="text/css">
	.center{
		text-align: center;
	}
	th{
		text-align: center;
		padding: 5px;
	}
	td{
		padding: 5px;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<form action="" method="GET">
		<!-- <input type="date" name="date_from" class="" value="<?= $datefrom ?>" /> -->
		<input type="date" name="date_to" class="" value="<?= $dateto ?>" />
		<button type="submit">Submit</button> <input type="button" onclick="tableToExcel('exportTable', 'Cash Journal - <?= date("m/d/Y") ?>')" value="Export to Excel">
		</form>
		<br><br>
		<table id="exportTable" width="100%" border="0" style="">
			<tr>
				<td class="center" colspan="12" style="border-left: 0px;border-right: 0px;padding: 0px;">
						<h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
						<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br>
						For the Month Ended: <?=date('F j, Y',strtotime($dateto)) ?><br><br>
					<h3 style="margin:0px;"><?= $this->title ?></h3>
					<br>
				</td>
			</tr>
			<tr>
				<td width="50%" style="vertical-align: top;">
					<table width="100%">
						<tr><th colspan="2">ASSETS</th></tr>
						<?php
						//var_dump($asset);
							foreach ($asset as $key => $val) {
								$code = Uacs::find()->where(['uacs'=>$val['object_code']])->one();
								echo '
									<tr>
										<td width="70%">'.$code->object_code.'</td>
										<td class="rightni">'.number_format($val['amount'],2).'</td>
									</tr>
								';
								$totalasset += $val['amount'];
							}
							echo '<tr><td><i>Less:</i></td></tr>';
							foreach ($depreciations as $key => $val) {
								$code = Uacs::find()->where(['uacs'=>$val['object_code']])->one();
								echo '
									<tr>
										<td width="70%" style="padding-left:50px;"><i>'.$code->object_code.'</i></td>
										<td class="rightni"><i>('.number_format($val['amount'],2).')</i></td>
									</tr>
								';
								$totaldep += $val['amount'];
							}
						?>
					</table>
				</td>
				<td style="vertical-align: top;">
					<table width="100%">
						<tr><th colspan="2">LIABILITIES</th></tr>
						<?php
						//var_dump($asset);
							foreach ($liabilities as $key => $val) {
								$code = Uacs::find()->where(['uacs'=>$val['object_code']])->one();
								echo '
									<tr>
										<td width="70%">'.$code->object_code.'</td>
										<td class="rightni">'.number_format($val['amount'],2).'</td>
									</tr>
								';
								$totalliabilities += $val['amount'];
							}
							
						?>
						<tr>
							<th style="text-align: left;">Total Liabilities</th>
							<th class="rightni">
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<?= number_format($totalliabilities,2) ?>
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<hr style="border-color:black;padding: 0px;margin-top: 4px;">
							</th>
						</tr>
					</table>
					<table width="100%">
						<tr><th colspan="2">EQUITY</th></tr>
						<?php
						//var_dump($asset);
							foreach ($equity as $key => $val) {
								$code = Uacs::find()->where(['uacs'=>$val['object_code']])->one();
								echo '
									<tr>
										<td width="70%">'.$code->object_code.'</td>
										<td class="rightni">'.number_format($val['amount'],2).'</td>
									</tr>
								';
								$totalequity += $val['amount'];
							}
							if($rebeg>0){
								echo '
									<tr>
										<td width="70%">Retained Earnings</td>
										<td class="rightni">'.number_format($rebeg,2).'</td>
									</tr>
								';
							}
							
						?>
						<tr>
							<th style="text-align: left;">Total Equity</th>
							<th class="rightni">
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<?= number_format($totalequity+$rebeg,2) ?>
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<hr style="border-color:black;padding: 0px;margin-top: 4px;">
							</th>
						</tr>
					</table>
					
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%">
						<tr>
							<th style="text-align: left;vertical-align: top;">Total Assets</th>
							<th class="rightni">
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<?= number_format($totalasset-$totaldep,2) ?>
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<hr style="border-color:black;padding: 0px;margin-top: 4px;">
							</th>
						</tr>
					</table>
				</td>
				<td>
					<table width="100%">
						<tr>
							<th style="text-align: left;vertical-align: top;">Total Liabilities + Equity</th>
							<th class="rightni">
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<?= number_format($totalliabilities+($totalequity+$rebeg),2) ?>
								<hr style="border-color:black;padding: 0px;margin: 0px;">
								<hr style="border-color:black;padding: 0px;margin-top: 4px;">
							</th>
						</tr>
					</table>
				</td>
			</tr>
			
		</table>
	</div>
</div>
<script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()
</script>