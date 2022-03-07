<?php
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\Uacs;
use app\models\JevEntries;

$this->title = 'Subsidiary Ledger';
$this->params['breadcrumbs'][] = $this->title;

if(!isset($_POST['from'])){
	$from = date('Y-m-01');
}else{
	$from = $_POST['from'];
}
if(!isset($_POST['to'])){
	$to = date('Y-m-t');
}else{
	$to = $_POST['to'];
}
if(!isset($_POST['account'])){
	$account = 10101000;
}else{
	$account = $_POST['account'];
}

$totaldebit=0;
$totalcredit=0;
$cummulativetotal=0;

$uacs = Uacs::find()->where(['uacs'=>$account])->one();
if($uacs->classification=='Asset'||$uacs->classification=='Liabilities'||$uacs->classification=='Equity'){
	$type = 'real';
	$bb =
	JevEntries::find()
	->joinWith('jev0')
	->where(['accounting_code'=>$account,'type'=>'debit'])
	->andWhere(['<', 'date_posted',$from])
	->sum('amount')
	-
	JevEntries::find()
	->joinWith('jev0')
	->where(['accounting_code'=>$account,'type'=>'credit'])
	->andWhere(['<', 'date_posted',$from])
	->sum('amount');
	$cummulativetotal += $bb;
}else{
	$type = 'nominal';
}

$jeventries=JevEntries::find()
	->joinWith('jev0')
	->where(['accounting_code'=>$account])
	->andWhere(['between', 'date_posted', $from, $to ])
	->all();

?>
<style type="text/css">
	th,td{
		padding: 10px;
	}
</style>
<div class="row">
	<div class="col-md-12">
		<center><h2>Subsidiary Ledger (per account)</h2></center>
		<hr>
	</div>
	<form action="" method="POST">
	    	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	<div class="form-group">
		<div class="col-md-2">
			<label class="form-label">From</label>
			<input type="date" class="form-control" name="from" value="<?= $from ?>">
		</div>
		<div class="col-md-2">
			<label class="form-label">To</label>
			<input type="date" class="form-control" name="to" value="<?= $to ?>">
		</div>
		<div class="col-md-3">
			<label class="form-label">Account</label>
			<?php
			echo Select2::widget([
			    'name' => 'account',
			    'value'=>$account,
			    'data' => ArrayHelper::map(Uacs::find()->where(['isEnabled'=>1])->all(), 'uacs', 'object_code'),
			    'options' => [
			        'placeholder' => 'Select account ...',
			    ],
			]);
			?>
		</div>
		<div class="col-md-2">
			<br>
			<button class="btn btn-primary">Submit</button>
		</div>
	</div>
	</form>
	<div class="col-md-12">
		<hr>
		<table width="100%" border="1">
			<tr>
				<th width="2%" class="center">#</th>
				<th width="5%" class="center">Year</th>
				<th width="5%" class="center">Month</th>
				<th width="5%" class="center">Day</th>
				<th width="40%" class="center">Particulars</th>
				<th class="center">Debit</th>
				<th class="center">Credit</th>
				<th class="center">Cummulative Balance</th>
			</tr>
			<?php
			$i=1;
			if($type=='real'){
				echo '<tr>';
				echo '<td class="center"><i>'.$i++.'</i></td>';
				echo '<td class="center"><i>'.date('Y',strtotime($from)).'</i></td>';
				echo '<td class="center"><i>'.date('m',strtotime($from)).'</i></td>';
				echo '<td class="center"><i>'.date('d',strtotime($from)).'</i></td>';
				echo '<td><i>Beginning balance..</td>';
				echo '<td class="rightni"><i>'.($bb>0?number_format($bb,2):'0.00').'</i></td>';
				echo '<td class="rightni"><i>'.($bb<0?number_format($bb,2):'').'</i></td>';
				if($cummulativetotal<0){$cummulativetotal=$cummulativetotal*-1;}
				echo '<td class="rightni"><i>'.number_format($cummulativetotal,2).'</i></td>';
				echo '</tr>';
			}
			foreach ($jeventries as $key) {
				echo '<tr>';
				echo '<td class="center">'.$i++.'</td>';
				echo '<td class="center">'.date('Y',strtotime($key->jev0->date_posted)).'</td>';
				echo '<td class="center">'.date('m',strtotime($key->jev0->date_posted)).'</td>';
				echo '<td class="center">'.date('d',strtotime($key->jev0->date_posted)).'</td>';
				echo '<td>'.$key->jev0->remarks.'</td>';
				echo '<td class="rightni">'.($key->type=='debit'?number_format($key->amount,2):'').'</td>';
				echo '<td class="rightni">'.($key->type=='credit'?number_format($key->amount,2):'').'</td>';
				if($key->type=='debit'){
					$cummulativetotal+=$key->amount;
				}else{
					$cummulativetotal-=$key->amount;
				}
				if($cummulativetotal<0){
					echo '<td class="rightni">'.number_format(($cummulativetotal*-1),2).'</td>';
				}else{
					echo '<td class="rightni">'.number_format($cummulativetotal,2).'</td>';
				}
				echo '</tr>';
			}
			?>
		</table>
	</div>
</div>