<?php

use yii\helpers\Html;
use app\models\Settings;


/* @var $this yii\web\View */
/* @var $model app\models\DvTracking */

$this->title = $vouchertype.' Voucher';
$this->params['breadcrumbs'][] = ['label' => 'Dv Trackings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-tracking-create">

    <center>
    	<h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
    	<?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br><br>
    	<h1><?= Html::encode($this->title) ?></h1>
    </center>
    <hr>
    <?= $this->render('_form', [
        'model' => $model,
        'apmodel' => $apmodel,
        'creditaccount' => $creditaccount,
    ]) ?>

</div>
