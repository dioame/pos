<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Capitals;
use app\models\DvTracking;
use app\models\Sales;
use app\models\PaymentsReceivable;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Journal';
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


$i=1;

$entries = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10101000])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->all();

$balance = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10101000,'type'=>'debit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount')-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10101000,'type'=>'credit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount');

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
						<h3 style="margin:0px;">Cash Journal</h3>
						<br><br>

				</td>
			</tr>
			<tr>
				<th>#</th>
				<th>Year</th>
				<th>Month</th>
				<th>Date</th>
				<th width="30%">Particulars</th>
				<th>Reference</th>
				<th width="15%">Total Cash Receipts<br><small>(Capital Contributions, Cash Revenues, Collections of Receivables)</small></th>
				<th width="15%">Total Cash Disbursements<br><small>(Cash Disbursements, Payment of Payables)</th>
				<th width="8%">Cumulative Balance</th>
			</tr>
			<tr>
				<td><?= $i++ ?></td>
				<td><?= date('Y',strtotime($datefrom)) ?></td>
				<td><?= date('m',strtotime($datefrom)) ?></td>
				<td><?= date('d',strtotime($datefrom)) ?></td>
				<td><i><b>Add previous balance...</b></i></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="rightni"><?= number_format($balance,2) ?></td>
			</tr>
<?php
	foreach ($entries as $key) {
		$string = "";
		echo '<tr>';
		echo '<td>'.$i++.'</td>';
		echo '<td>'.date('Y',strtotime($key->jev0->date_posted)).'</td>';
		echo '<td>'.date('m',strtotime($key->jev0->date_posted)).'</td>';
		echo '<td>'.date('d',strtotime($key->jev0->date_posted)).'</td>';
		echo '<td>'.$key->jev0->remarks.'</td>';
		if($model = Capitals::find()->where(['jev'=>$key->jev])->one()){
			$string .= 'AR-'.$model->arNo.'<br>';
		}elseif($model = DvTracking::find()->where(['jev'=>$key->jev])->one()){
			$string .= 'DV-'.$model->dv_number.'<br>';
		}elseif($model = Sales::find()->where(['jev'=>$key->jev])->one()){
			$string .= 'AR-'.$model->or->tracking.'<br>';
		}elseif($model = PaymentsReceivable::find()->where(['jev'=>$key->jev])->one()){
			$string .= 'AR-'.$model->or->tracking.'<br>';
		}
		/*switch ($key->jev0->source) {
			case 'capitals':
				$model = Capitals::find()->where(['jev'=>$key->jev])->one();
				echo '<td>AR-'.$model->arNo.'</td>';
				break;

			case 'expenses':
				$model = DvTracking::find()->where(['jev'=>$key->jev])->one();
				echo '<td>DV-'.$model->dv.'</td>';
				break;

			case 'sales':
				$model = Sales::find()->where(['jev'=>$key->jev])->one();
				echo '<td>AR-'.$model->or->tracking.'</td>';
				break;
			
			default:
				echo '<td></td>';
				break;
		}*/
		echo '<td>'.$string.'</td>';
		echo $key->type=='debit'?'<td class="rightni">'.number_format($key->amount,2).'</td><td></td>':'<td></td><td class="rightni">'.number_format($key->amount,2).'</td>';
		$key->type=='debit'?$balance+=$key->amount:$balance-=$key->amount;
		echo '<td class="rightni">'.number_format($balance,2).'</td>';
		echo '</tr>';
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