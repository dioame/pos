<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\models\Products;
use app\models\SalesDetails;
use app\models\Settings;
use app\models\StockCard;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Production Report';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
	$t1 = strtotime($a[0]);
	$t2 = strtotime($b[0]);
	return $t1-$t2;
}

function calculatehours($d1,$d2){
    $date1 = new DateTime($d1);
    $date2 = new DateTime($d2);

    $diff = $date2->diff($date1);

    $hours = $diff->h;
    $hours = $hours + ($diff->days*24);

    return $hours;
}

if(isset($_POST['date_from'])){
	$datefrom = date('Y-m-d',strtotime($_POST['date_from']));
}else{
	$datefrom = '2018-01-01';
}

if(isset($_POST['date_to'])){
	$dateto = date('Y-m-d',strtotime($_POST['date_to']));
}else{
	$dateto = date('Y-m-d');
}

?>
<style type="text/css">
	.center{
		text-align: center;
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
		<form action="" method="POST">
		<input type="date" name="date_from" class="" value="<?= $datefrom ?>" />
		<input type="date" name="date_to" class="" value="<?= $dateto ?>" />
		<button type="submit">Submit</button> <input type="button" onclick="tableToExcel('exportTable', 'Production Report - <?= date("m/d/Y") ?>')" value="Export to Excel">
		</form>
		<br><br>
		<table id="exportTable" width="100%" border="0" style="border-bottom: 1px solid black;">
			<tr>
				<td class="center" colspan="12" style="border-left: 0px;border-right: 0px;padding: 0px;">
					<h3 style="margin:0px;">Production Report</h3>
						<h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
						<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br>
						For the Period: <?= date('F j - ',strtotime($datefrom)).date('F j, 2018',strtotime($dateto)) ?><br><br>
				</td>
			</tr>
			<tr>
				<th rowspan="2" width="30%">Particulars</th>
				<th rowspan="2" width="7%">In</th>
				<th colspan="2">Weight</th>
				<th colspan="3">Time</th>
				<th colspan="2">Initials</th>
			</tr>
			<tr>
				<th>Weight per blocks (kg)</th>
				<th>Total weight per block (kg)</th>
				<th>Started</th>
				<th>Finished</th>
				<th>Duration</th>
				<th>Prepared by: Caretaker</th>
				<th>Checked by: Manager</th>
			</tr>
			<tr>
				<th>a</th>
				<th width="5%">b</th>
				<th width="5%">c</th>
				<th>d = b*c</th>
				<th>e</th>
				<th>f</th>
				<th>g</th>
				<th>h</th>
				<th>i</th>
			</tr>
<?php
			$previousbalance = 0;
			$previousstocks = StockCard::find()->where(['<','date',$datefrom])->sum('added');
			$previoussales = SalesDetails::find()->joinWith('sales')->where(['<','sales.transaction_date',$datefrom])->sum('quantity');
			$previousbalance = $previousstocks-$previoussales;

			echo '<tr>';
			echo '<td><i><b>Add previous balance</b></i></td>';
			echo '<td class="center">'.$previousbalance.'</td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '</tr>';

			$all= [];
			$products = Products::find()->all();
			foreach ($products as $products) {
				$stocks = StockCard::find()->where(['sku'=>$products->sku])->andWhere(['>=','date(date)',$datefrom])->andWhere(['<=','date(date)',$dateto])->all();
				
				foreach ($stocks as $stocks) {
					array_push($all,[$stocks->date,$stocks->id,'stocks']);
				}
			}

			usort($all,"date_compare");
			$balance = $previousbalance;
			foreach ($all as $all) {
				echo '<tr>';
				$totalweight = 0;
					$stockcard = StockCard::findOne($all[1]);
					$balance += $stockcard->added;
					$totalweight = $balance*20;
					echo '<td>Daily production of '.$stockcard->product->product_name.' '.$stockcard->remarks.'</td>';
					echo '<td class="center">'.$stockcard->added.'</td>';
					echo '<td class="center">20</td>';
					echo '<td class="center">'.($totalweight>0?$totalweight:'').'</td>';
					echo '<td>'.date('m/d/y G:i:s a',strtotime($stockcard->date)).'</td>';
					echo '<td>'.date('m/d/y G:i:s a',strtotime($stockcard->finished)).'</td>';
					echo '<td>'.calculatehours($stockcard->date,$stockcard->finished).'</td>';
					echo '<td></td>';
					echo '<td></td>';
				/*echo '<td>'.date('F j, Y',strtotime($all[0])).'</td>';
				echo '<td>'.($all[2]=='sales'?'Sold '.SalesDetails::findOne($all[1])->quantity.' '.SalesDetails::findOne($all[1])->product->product_name.' @ &#8369;'.number_format(SalesDetails::findOne($all[1])->product_price,2).'/block':'Daily production of '.StockCard::findOne($all[1])->product->product_name.' '.StockCard::findOne($all[1])->remarks).'</td>';
				echo '</tr>';*/
			}
?>
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