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

$this->title = 'Changes in Retained Earnings';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
	$t1 = strtotime($a[0]);
	$t2 = strtotime($b[0]);
	return $t1-$t2;
}

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


//getting previous retained earnings
$previoussales = 0;
$previousexpenses = 0;
$previouscre = 0;

$previoussales = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>40202160,'type'=>'credit'])->andWhere(['<=','date(date_posted)',$datefrom])->sum('amount');
$previousexpenses = JevEntries::find()->joinWith('jev0')->joinWith('accountingCode')->where(['AND',['classification'=>'Expenses'],['type'=>'debit']])->andWhere(['<=','date(date_posted)',$datefrom])->sum('amount');
$previouscre = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>20000001,'type'=>'credit'])->andWhere(['<=','date(date_posted)',$datefrom])->sum('amount');
$rebeg = $previoussales-$previousexpenses-$previouscre;


//current

$totalsales = 0;
$totalexpense = 0;

$totalsales = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>40202160,'type'=>'credit'])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->sum('amount');

$expensejournal = JevEntries::find()->joinWith('jev0')->joinWith('accountingCode')->select('*, sum(jev_entries.amount) AS amount')->where(['AND',['classification'=>'Expenses'],['type'=>'debit']])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->groupBy('accounting_code')->all();

$dividends = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>20000001,'type'=>'credit'])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->sum('amount');

foreach ($expensejournal as $key => $val) {
	$totalexpense += $val['amount'];
}
$netincome = $totalsales-$totalexpense;
	
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
<div class="row">
	<div class="col-md-12" style='font-family: "Open Sans", Helvetica, Arial, sans-serif;'>
		<table id="exportTable" width="100%" border="0" style="">
			<tr>
				<td colspan="12">
					<div class="col-md-offset-3 col-md-6 col-md-offset-3">
						<table width="100%">
							<tr>
								<th>Retained Earnings BB</th>
								<th width="15%"></th>
								<th width="15%" class="rightni"><?= number_format($rebeg,2) ?></th>
							</tr>
							<tr>
								<td style="padding-left: 50px;">Add: Net Income</td>
								<td></td>
								<td class="rightni"><?= number_format($netincome,2) ?></td>
							</tr>
							<tr>
								<td style="padding-left: 50px;">Less: Dividends</td>
								<td class="rightni"><?= number_format($dividends,2) ?></td>
								<td></td>
							</tr>
							<tr>
								<th>Retained Earnings End</th>
								<th></th>
								<th class="rightni"><?= number_format(($rebeg+$netincome-$dividends
									),2) ?></th>
							</tr>
						</table>
					</div>
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