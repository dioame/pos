<?php
use app\models\SalesDetails;
use app\models\CashDecDet;

$this->title = 'End of Day Report';
$this->params['breadcrumbs'][] = $this->title;

$cashdecdetails = CashDecDet::find()->joinWith('cashDec')->where(['date_posted'=>$date])->all();
$temparr = [];
$changeamount = 0;
$onethousand = 0;
$fivehundred = 0;
$twohundred = 0;
$onehundred = 0;
$fifty = 0;
$twenty = 0;
$ten = 0;
$five = 0;
$one = 0;
$fiftycents = 0;
$twentyfivecents = 0;
if(count($cashdecdetails)>0){
	
	foreach ($cashdecdetails as $key) {
		$value = 0;
		switch ($key->type) {
			case 'changeamount':$changeamount = $key->count;break;
			case 'onethousand':$onethousand = $key->count;break;
			case 'fivehundred':$fivehundred = $key->count;break;
			case 'twohundred':$twohundred = $key->count;break;
			case 'onehundred':$onehundred = $key->count;break;
			case 'fifty':$fifty = $key->count;break;
			case 'twenty':$twenty = $key->count;break;
			case 'ten':$ten = $key->count;break;
			case 'five':$five = $key->count;break;
			case 'one':$one = $key->count;break;
			case 'fiftycents':$fiftycents = $key->count;break;
			case 'twentyfivecents':$twentyfivecents = $key->count;break;
			
			default:
				# code...
				break;
		}
	}
}

?>

<style type="text/css">
	th,td{
		padding: 5px;
	}
	th{
		border: 1px solid black;
	}
	th{
		text-align: center;
	}
	.rowheader{
		background-color: black;
		color:white;
		border: 1px solid black;
	}
	tr{
		 border-left: 1px solid black;border-right: 1px solid black;
	}
	.salespec{
		border-right: 1px solid black;
	}
	.piece{
		width: 100%;
		text-align: right;
		border:none;
	}
	.piece:focus {
	    outline: -webkit-focus-ring-color auto 5px;
	    outline-color: #ffffff;
	    outline-style: auto;
	    outline-width: 5px;
	}
	.piece:disabled{
		background-color: #ffffff;
	}
</style>
<form action="">
	<input type="date" name="date" value="<?= $date ?>">
	<button type="submit">Submit</button>
</form>
<form action="eodsave" method="POST">
	<input type="hidden" name="date" value="<?= $date ?>">
<br>
<table width="100%">
	<tr>
		<th colspan="8" class="rowheader"><center>Daily Cashier's End-of-day Report</center></th>
	</tr>
	<tr>
		<td colspan="8" style="border-left: 1px solid black;border-right: 1px solid black;">Date: <?= date('m/d/Y',strtotime($date)) ?></td>
	</tr>
	<tr>
		<td colspan="4" style="border-left: 1px solid black;">Prepared by: O&M Cashier</td>
		<td colspan="4" style="border-right: 1px solid black;">Change Fund Amount: P <input type="number" id="changeamount" name="changeamount" style="text-align: right;" value="<?= $changeamount ?>"></td>
	</tr>
	<tr>
		<th colspan="8" class="rowheader">Sales Report</th>
	</tr>
	<tr>
		<th>OR No.</th>
		<th>Payer</th>
		<th>Particulars</th>
		<th>Category</th>
		<th>Cash Sales/Advance</th>
		<th>Receivables</th>
		<th>Total Amount</th>
		<th>Remarks</th>
	</tr>
	<?php
	$totaliceblocks=0;
	$totaliceblockscash=0;
	$totalcash=0;
	$totalreceivable=0;
	foreach ($model as $key) {

		$salesspec = SalesDetails::find()->where(['sales_id'=>$key->id])->one();
		$remarks = 'Sold '.$salesspec->quantity.' '.$salesspec->product->product_name.' @ P'.$salesspec->product_price.'/block';

		echo '<tr>';
		echo '<td class="salespec">'.date('Y-m-',strtotime($salesspec->sales->transaction_date)).$key->or->tracking.'</td>';
		echo '<td class="salespec">'.$key->customer->firstname.' '.$key->customer->lastname.'</td>';
		echo '<td class="salespec">'.$remarks.'</td>';
		echo '<td class="salespec">Ice Blocks</td>';
		echo '<td class="rightni salespec">'.number_format($key->amount_paid,2).'</td>';
		echo '<td class="rightni salespec">'.number_format($key->sales_on_credit,2).'</td>';
		echo '<td class="rightni salespec">'.number_format(($key->amount_paid+$key->sales_on_credit),2).'</td>';
		echo '<td class="salespec"></td>';
		echo '</tr>';
		$totaliceblocks+=($key->amount_paid+$key->sales_on_credit);
		$totalcash+=$key->amount_paid;
		$totaliceblockscash+=$key->amount_paid;
		$totalreceivable=$key->sales_on_credit;
	}

	?>
	<tr style="border-top: 1px solid black;"><td colspan="8">&nbsp;</td></tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;">Cash Sales</td>
		<td colspan="2" class="rightni" style="border:1px solid black;"><?= number_format($totalcash,2) ?></td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;">Collectibles</td>
		<td colspan="2" class="rightni" style="border:1px solid black;"><?= number_format($totalreceivable,2) ?></td>
		<td colspan="4"></td>
	</tr>
	<tr>
		<td></td>
		<th style="border:1px solid black;text-align: left;">Total Sales</th>
		<td colspan="2" class="rightni" style="border:1px solid black;"><?= number_format($totalcash+$totalreceivable,2) ?></td>
		<td colspan="4"></td>
	</tr>
	<tr><td colspan="8">&nbsp;</td></tr>
	<tr>
		<td></td>
		<th colspan="2" class="rowheader" style="border: 1px solid black;">Daily Collection</th>
		<td></td>
		<th colspan="3" class="rowheader" style="border: 1px solid black;">Fund Denominations</th>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<th style="border:1px solid black;text-align: left;">Category</th>
		<th class="rightni" style="border:1px solid black;">Amount</th>
		<td></td>
		<th style="border:1px solid black;text-align: center;">Bills</th>
		<th style="border:1px solid black;text-align: center;">Pieces</th>
		<th style="border:1px solid black;text-align: center;">Amount</th>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Ice Blocks</td>
		<td class="rightni" style="border:1px solid black;"><?= number_format($totaliceblockscash,2) ?></td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">1,000.00</td>
		<td style="border:1px solid black;"><input type="number" name="onethousand" id="1000" class="piece" value="<?= $onethousand ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" name="onethousandtotal" id="onethousandtotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Collection from Receivables</td>
		<td class="rightni" style="border:1px solid black;">-</td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">500.00</td>
		<td style="border:1px solid black;"><input type="number" name="fivehundred" id="500" class="piece" value="<?= $fivehundred ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" name="fivehundredtotal" id="fivehundredtotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Other Revenues</td>
		<td class="rightni" style="border:1px solid black;">-</td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">200.00</td>
		<td style="border:1px solid black;"><input type="number" name="twohundred" id="200" class="piece" value="<?= $twohundred ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="twohundredtotal" name="twohundredtotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<th style="border:1px solid black;text-align: left;">Total</th>
		<th class="rightni" style="border:1px solid black;"><?= number_format($totalcash,2) ?></th>
		<td></td>
		<td style="border:1px solid black;text-align: right;">100.00</td>
		<td style="border:1px solid black;"><input type="number" name="onehundred" id="100" class="piece" value="<?= $onehundred ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="onehundredtotal" name="onehundredtotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="4"></td>
		<td style="border:1px solid black;text-align: right;">50.00</td>
		<td style="border:1px solid black;"><input type="number" name="fifty" id="50" class="piece" value="<?= $fifty ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="fiftytotal" name="fiftytotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<th style="border:1px solid black;" colspan="2" class="rowheader">Summary</th>
		<td></td>
		<td style="border:1px solid black;text-align: right;">20.00</td>
		<td style="border:1px solid black;"><input type="number" name="twenty" id="20" class="piece" value="<?= $twenty ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="twentytotal" name="twentytotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Change Fund</td>
		<td class="rightni" style="border:1px solid black;"><input type="text" id="changefund" style="text-align: right;" class="piece" disabled></td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">10.00</td>
		<td style="border:1px solid black;"><input type="number" name="ten" id="10" class="piece" value="<?= $ten ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="tentotal" name="tentotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Add: Collection</td>
		<td class="rightni" style="border:1px solid black;"><?= number_format($totalcash,2) ?></td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">5.00</td>
		<td style="border:1px solid black;"><input type="number" name="five" id="5" class="piece" value="<?= $five ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="fivetotal" name="fivetotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Expected Cash</td>
		<td class="rightni" style="border:1px solid black;"><input type="text" id="expectedcash" disabled class="piece" value="<?= $totalcash ?>"></td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">1.00</td>
		<td style="border:1px solid black;"><input type="number" name="one" id="1" class="piece" value="<?= $one ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="onetotal" name="onetotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Cashier Declaration</td>
		<td class="rightni" style="border:1px solid black;"><input type="text" id="cashierdec" disabled class="piece"></td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">0.50</td>
		<td style="border:1px solid black;"><input type="number" name="fiftycents" id="0.5" class="piece" value="<?= $fiftycents ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="fiftycentstotal" name="fiftycentstotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td style="border:1px solid black;text-align: left;">Discrepancy, if any</td>
		<td class="rightni" style="border:1px solid black;"><input type="text" id="discrepancy" disabled class="piece" value="0"></td>
		<td></td>
		<td style="border:1px solid black;text-align: right;">0.25</td>
		<td style="border:1px solid black;"><input type="number" name="twentyfivecents" id="0.25" class="piece" value="<?= $twentyfivecents ?>"></td>
		<td style="border:1px solid black;text-align: right;" width="18%"><input type="text" id="twentyfivecentstotal" name="twentyfivecentstotal" class="piece total" value="0" disabled></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="4"></td>
		<td style="border:1px solid black;text-align: right;">Total</td>
		<td style="border:1px solid black;text-align: right;"></td>
		<td style="border:1px solid black;text-align: right;"><input type="text" id="grandtotal" disabled class="piece"></td>
		<td></td>
	</tr>
	<tr style="border-bottom: 1px solid black;"><td colspan="8">&nbsp;</td></tr>
	<tr style="border-bottom: 1px solid black;"><td colspan="8" style="text-align: center;"><button class="btn btn-success">Save</button></td></tr>
</table>
</form>
<script type="text/javascript">
	$('input.piece').bind('keyup mouseup', function () {
		var tempcount = 0;
		tempcount++;
		//alert($(this).attr('name'));
		var tempvar = $(this).attr('name')+'total';
		var val = parseFloat($(this).attr('id'));
		//alert(tempvar);
	  	$('#'+tempvar).val(val*$(this).val());
	  	
	  	//alert(sum);
	  	getTotal();
	});
	$('input#changeamount').bind('keyup mouseup', function () {
	  	$('#changefund').val($('#changeamount').val());
	  	var change = parseFloat($('#changeamount').val());
	  	var val = <?= $totalcash ?>+change;
	  	$('#expectedcash').val(val);
	  	getTotal();
	});
	function getTotal(){
		var sum = 0;
		$(".total").each(function(){
	  	        sum += +$(this).val();
	  	    });
		$('#grandtotal').val(sum);
		$('#cashierdec').val(sum);

		
		discrepancy();
	}
	function discrepancy(){
		$('#discrepancy').val($('#cashierdec').val()-$('#expectedcash').val());
	}
	function multiply(){
		$(".total").each(function(){
				var id = $(this).attr('name').replace('total','');
				var data = parseFloat($( "input[name='"+id+"']" ).attr('id'))*parseFloat($( "input[name='"+id+"']" ).attr('value'));
				$(this).val(data);
	  	    });
	}
	multiply();
	getTotal();
	
</script>