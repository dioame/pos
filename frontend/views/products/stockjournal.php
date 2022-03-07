<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\models\Products;
use app\models\SalesDetails;
use app\models\Settings;
use app\models\StockCard;
use app\models\InventoryLoss;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Stocks Journal';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
	$t1 = strtotime($a[0]);
	$t2 = strtotime($b[0]);
	return $t1-$t2;
}

if(isset($_POST['date_from'])){
	$datefrom = date('Y-m-d',strtotime($_POST['date_from']));
}else{
	$datefrom = date('Y-m-01');
}

if(isset($_POST['date_to'])){
	$dateto = date('Y-m-d',strtotime($_POST['date_to']));
}else{
	$dateto = date('Y-m-t');
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
						<h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
						<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br>
						For the Period: <?= date('F j - ',strtotime($datefrom)).date('F j, 2018',strtotime($dateto)) ?><br><br>
						<h3 style="margin:0px;">Stocks Journal</h3>
						<br><br>
				</td>
			</tr>
			<tr>
				<th>Date</th>
				<th>Particulars</th>
				<th>Reference</th>
				<th width="5%">In</th>
				<th width="5%">Out</th>
				<th width="5%">Balance</th>
			</tr>
<?php
			$previousbalance = 0;
			$previousstocks = StockCard::find()->where(['<','date',$datefrom])->sum('added');
			$previousloss = InventoryLoss::find()->where(['<','date_posted',$datefrom])->sum('quantity');
			$previoussales = SalesDetails::find()->joinWith('sales')->where(['<','sales.transaction_date',$datefrom])->sum('quantity');
			$previousbalance = $previousstocks-$previoussales-$previousloss;

			echo '<tr>';
			echo '<td></td>';
			echo '<td><i><b>Add previous balance</b></i></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td></td>';
			echo '<td class="center">'.$previousbalance.'</td>';
			echo '</tr>';

			$all= [];
			$products = Products::find()->all();
			foreach ($products as $products) {
				$sales = SalesDetails::find()->joinWith('sales')->where(['product_id'=>$products->id])->andWhere(['>=','date(sales.transaction_date)',$datefrom])->andWhere(['<=','date(sales.transaction_date)',$dateto])->all();
				$stocks = StockCard::find()->where(['sku'=>$products->sku])->andWhere(['>=','date(date)',$datefrom])->andWhere(['<=','date(date)',$dateto])->all();
				$loss = InventoryLoss::find()->where(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->andWhere(['pID'=>$products->id])->all();
				
				foreach ($stocks as $stocks) {
					array_push($all,[$stocks->date,$stocks->id,'stocks']);
				}
				foreach ($sales as $sales) {
					array_push($all,[$sales->sales->transaction_date,$sales->id,'sales']);
				}
				foreach ($loss as $loss) {
					array_push($all,[$loss->date_posted,$loss->id,'loss']);
				}
			}

			usort($all,"date_compare");
			$balance = $previousbalance;
			foreach ($all as $all) {
				echo '<tr>';
				$totalweight = 0;
				if($all[2]=='sales'){
					$salesdetails = SalesDetails::findOne($all[1]);
					$balance -= $salesdetails->quantity;
					$totalweight = $balance*20;
					echo '<td>'.date('m/j/Y',strtotime($all[0])).'</td>';
					echo '<td>Sold '.$salesdetails->quantity.' '.$salesdetails->product->product_name.' @ &#8369;'.number_format($salesdetails->product_price,2).'/block'.'</td>';
					echo '<td>SID-'.date('Y-m-',strtotime($salesdetails->sales->transaction_date)).$salesdetails->sales->id.'</td>';
					echo '<td></td>';
					echo '<td class="center">'.$salesdetails->quantity.'</td>';
					echo '<td class="center">'.$balance.'</td>';
				}elseif($all[2]=='loss'){
					$lossdetails = InventoryLoss::findOne($all[1]);
					$balance -= $lossdetails->quantity;
					echo '<td>'.date('m/j/Y',strtotime($all[0])).'</td>';
					echo '<td> Inventory Loss</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td class="center">'.$lossdetails->quantity.'</td>';
					echo '<td class="center">'.$balance.'</td>';
				}else{
					$stockcard = StockCard::findOne($all[1]);
					$balance += $stockcard->added;
					$totalweight = $balance*20;
					echo '<td>'.date('m/j/Y',strtotime($all[0])).'</td>';
					echo '<td>Daily production of '.$stockcard->product->product_name.' '.$stockcard->remarks.'</td>';
					echo '<td>PRD-'.date('Y-m-',strtotime($stockcard->date)).$stockcard->id.'</td>';
					echo '<td class="center">'.$stockcard->added.'</td>';
					echo '<td></td>';
					echo '<td class="center">'.$balance.'</td>';
				}
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