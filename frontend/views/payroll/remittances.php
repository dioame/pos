<?php
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\PayslipDeductionTypes;
use app\models\Employees;

$this->title = 'Remittances';
$this->params['breadcrumbs'][] = ['label' => 'Payroll', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if(!isset($_GET['deduction_type'])){
	$dtype = 0;
}else{
	$dtype = $_GET['deduction_type'];
}

?>
<style type="text/css">
	th, td{
		padding: 10px;
	}
</style>
<div class="row">
	<div class="col-md-12 no-padding">
		<form method="GET">
			<div class="col-md-3">
				<label class="control-label">Select Deduction</label>
				<?php
					echo Select2::widget([
					    'name' => 'deduction_type',
					    'data' => ArrayHelper::map(PayslipDeductionTypes::find()->all(), 'id', 'type'),
					    'options' => [
					        'placeholder' => 'Select deduction type ...',
					    ],
					]);
				?>
			</div>
			<div class="col-md-3">
				<br>
				<button type="submit" class="btn btn-primary">Select</button>
			</div>
		</form>
	</div>
	<div class="col-md-12">
		<hr>
	</div>
	<div class="col-md-12">
		<table width="100%" style="background-color: white;">
			<tr>
				<th class="center" width="20%">Name</th>
				<?php
					for($i=1;$i<=12;$i++){
						echo '<th class="center">'.date('M',strtotime('2018-'.$i.'-1')).'</th>';
					}
				?>
			</tr>
			<?php
				$employees = Employees::find()->all();
				foreach ($employees as $key) {
					echo '<tr>';
					echo '<td>'.$key->firstname.'</td>';
					echo '</tr>';
				}
			?>
		</table>
	</div>
</div>