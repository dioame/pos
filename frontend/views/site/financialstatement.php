<?php
use app\models\Settings;
?>
<div class="row">
	<div class="col-md-12">
		<center>
			<h3 style="margin: 0px;padding: 0px;">
				<b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b>
			</h3>
			<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br><br>
			<h1>Financial Statements</h1>
			<hr><br><br>
		</center>
	</div>
	<div class="col-md-12">
		<center>
			<h3>Income Statement</h3>
		</center>
	</div>
</div>