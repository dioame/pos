<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\models\Settings;
use app\models\JevTracking;
use app\models\JevEntries;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Journal';
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

$totaldebit = 0;
$totalcredit = 0;

$jev = JevTracking::find()->joinWith('jevEntries')->where(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->orderBy(['date_posted'=>SORT_ASC,'jev_tracking.id'=>SORT_ASC,'jev_entries.type'=>SORT_ASC])->all();

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
				<td class="center" colspan="12" style="border-left: 0px;border-right: 0px;padding: 0px;">
						<h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
						<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br>
						For the Period: <?= date('F j - ',strtotime($datefrom)).date('F j, 2018',strtotime($dateto)) ?><br><br>
					<h3 style="margin:0px;">General Journal</h3><br><br>
				</td>
			</tr>
			<tr>
				<th rowspan="2">#</th>
				<th rowspan="2">Year</th>
				<th rowspan="2">Month</th>
				<th rowspan="2">Date</th>
				<th rowspan="2">JEV#</th>
				<th colspan="2">Debit</th>
				<th colspan="2">Credit</th>
				<th rowspan="2">Remarks</th>
			</tr>
			<tr>
				<th width="18%">Account Title</th>
				<th width="8%">Amount</th>
				<th width="18%">Account Title</th>
				<th width="8%">Amount</th>
			</tr>
<?php
			$i=0;
			foreach ($jev as $jev) {
				$i++;
				$entrycount=0;
				$jeventry = JevEntries::find()->where(['jev'=>$jev->jev])->orderBy(['type'=>'SORT_DESC'])->all();
				$entrycount = count($jeventry);
				foreach ($jeventry as $entry) {
					echo '<tr>';
					if($entrycount!=0){
						echo '<td rowspan="'.$entrycount.'">'.$i.'</td>';
						echo '<td rowspan="'.$entrycount.'">'.date('Y',strtotime($jev->date_posted)).'</td>';
						echo '<td rowspan="'.$entrycount.'">'.date('m',strtotime($jev->date_posted)).'</td>';
						echo '<td rowspan="'.$entrycount.'">'.date('d',strtotime($jev->date_posted)).'</td>';
						echo '<td rowspan="'.$entrycount.'">'.$jev->jev.'</td>';
					}
					echo $entry->type=='debit'?'<td>'.$entry->accountingCode->object_code.'</td><td class="rightni">'.number_format($entry->amount,2).'</td><td></td><td></td>':'<td></td><td></td><td>'.$entry->accountingCode->object_code.'</td><td class="rightni">'.number_format($entry->amount,2);
					if($entrycount!=0){
						echo '<td rowspan="'.$entrycount.'">'.$jev->remarks.'</td>';
					}
					echo '</tr>';
					$entry->type=='debit'?$totaldebit+=$entry->amount:$totalcredit+=$entry->amount;
					$entrycount=0;
				}
				
			}
			
?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
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
			<td></td>
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