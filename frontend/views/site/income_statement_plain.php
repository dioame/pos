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

$this->title = 'Income Statement';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
	$t1 = strtotime($a[0]);
	$t2 = strtotime($b[0]);
	return $t1-$t2;
}

if(isset($_GET['date_from'])){
	$datefrom = date('Y-m-d',strtotime($_GET['date_from']));
}else{
	date('Y-m-01');
}

if(isset($_GET['date_to'])){
	$dateto = date('Y-m-d',strtotime($_GET['date_to']));
}else{
	date('Y-m-t');
}

$totalsales = 0;
$totalexpense = 0;

$salesjournal = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>40202160,'type'=>'credit'])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->sum('amount');

$totalsales += $salesjournal;

$expensejournal = JevEntries::find()->joinWith('jev0')->joinWith('accountingCode')->select('*, sum(jev_entries.amount) AS amount')->where(['AND',['classification'=>'Expenses'],['type'=>'debit']])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->groupBy('accounting_code')->all();




?>
<style type="text/css">
	.center{
		text-align: center;
	}
	th{
		text-align: center;
		padding: 5px;
		font-size:0.8em;
		color: #565656;
	}
	td{
		padding: 5px;
		font-size:0.8em;
		color: #565656;
	}
	hr{
		color: #565656;
		border-color: #565656;
		border-width: 0.5px;
	}
	.rightni{
		text-align: right;
	}
</style>
<div class="row" style='font-family: "Open Sans", Helvetica, Arial, sans-serif;'>
	<div class="col-md-12">
		<table id="exportTable" width="100%" border="0" style="">
			<tr>
				<td width="50%" style="vertical-align: top;">
					<table width="100%">
						<tr><th colspan="2">TOTAL SALES</th></tr>
						<tr>
							<td width="70%">Sales - Ice Blocks</td>
							<td class="rightni"><?= number_format($salesjournal,2) ?></td>
						</tr>
						<tr>
							<td>Other Income</td>
							<td class="rightni">-</td>
						</tr>
						<tr>
							<th style="text-align: left;">Gross Sales</th>
							<th class="rightni">
								<hr style="padding: 0px;margin: 0px;">
								<?= number_format($totalsales,2) ?>
								<hr style="padding: 0px;margin: 0px;">
								<hr style="padding: 0px;margin-top: 4px;">
							</th>
						</tr>
					</table>
				</td>
				<td>
					<table width="100%">
						<tr><th>TOTAL EXPENSE</th></tr>
						<?php
						//var_dump($expensejournal);
							foreach ($expensejournal as $key => $val) {
								$code = Uacs::find()->where(['uacs'=>$val['accounting_code']])->one();
								$totalexpense += $val['amount'];
								echo '
									<tr>
										<td width="70%">'.$code->object_code.'</td>
										<td class="rightni">'.number_format($val['amount'],2).'</td>
									</tr>
								';
							}
							
						?>
						<tr>
							<th style="text-align: left;">Total Expenses</th>
							<th class="rightni">
								<hr style="padding: 0px;margin: 0px;">
								<?= number_format($totalexpense,2) ?>
								<hr style="padding: 0px;margin: 0px;">
								<hr style="padding: 0px;margin-top: 4px;">
							</th>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td width="70%"><b>NET INCOME</b></td>
							<td class="rightni"><b><hr style="padding: 0px;margin: 0px;"><?= number_format($totalsales-$totalexpense,2) ?><hr style="padding: 0px;margin: 0px;"><hr style="padding: 0px;margin-top: 4px;"></b></td>
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