<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Petty Cash Establishment/Replenishment';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-offset-3 col-md-6 col-md-offset-3">
		<center><h1><?= Html::encode($this->title) ?></h1></center><hr>
	    <form action="" method="POST" onsubmit="return confirm('Do you really want to save this transaction?');">
	    	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	    	<label class="form-label">From Account</label>
	    	<select name="fundsource" class="form-control">
	    		<option value="10101000">Cash on Hand</option>
	    		<option value="10102010">Cash in Bank</option>
	    	</select>
	    	<br>
	    	<label class="form-label">Date Posted</label>
	    	<input type="date" class="form-control" name="date_posted" value="<?= date('Y-m-d') ?>">
	    	<br>
	    	<label class="form-label">Amount to be Established/Replenished</label>
	    	<input type="number" class="form-control" name="amount" value="0" style="text-align: right;" step=".01">
	    	<br>
	    	<label class="form-label">Remarks</label>
	    	<input type="text" class="form-control" name="reference" value="Replenishment of Petty Cash Fund">
	    	<br>
	    	<button class="btn btn-success">Save</button>
	    </form>
	</div>
</div>
