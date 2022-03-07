<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-md-4">
		<h1><?= Html::encode($this->title) ?></h1>
	    <form action="" method="POST" onsubmit="return confirm('Do you really want to save this transaction?');">
	    	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
	    	<label class="form-label">Date Posted</label>
	    	<input type="date" class="form-control" name="date_posted" value="<?= date('Y-m-d') ?>">
	    	<br>
	    	<label class="form-label">Amount</label>
	    	<input type="number" class="form-control" name="amount" value="0" style="text-align: right;" step=".01">
	    	<br>
	    	<label class="form-label">Reference Number</label>
	    	<input type="text" class="form-control" name="reference" >
	    	<br>
	    	<button class="btn btn-success">Save</button>
	    </form>
	</div>
</div>
