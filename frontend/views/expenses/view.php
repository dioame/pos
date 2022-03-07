<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Suppliers;
use app\models\DvTracking;
use app\models\Uacs;
use app\models\Employees;
use app\models\Settings;

$this->title = 'DV#'.$model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Disbursement Journal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model app\models\DvTracking */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
    td{
        padding: 6px;
    }
    tbody{
        border-color:#dedede;
    }
    table {
        display: table;
        border-collapse: collapse;
        border-spacing: 2px;
        border-color: #dedede;
    }
</style>
<div class="dv-tracking-form">

    <div class="row">

        <div class="col-md-offset-1 col-md-10 col-md-offset-1">
            <center><h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
            <?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><hr><h3>Disbursement Voucher</h3></center>
            <div class="col-md-offset-9 col-md-3">
                <span class="pull-right"><label>DV #: </label><?= $model->dv_number ?></span>
            </div>

            <div class="col-md-6">
                <label>Payee:</label>
                <?= $model->payee0?$model->payee0->supplier_name:'' ?>
            </div>

            <div class="col-md-offset-3 col-md-3">
                <span class="pull-right"><label>Date Posted: </label><?= $model->date_posted ?></span>
            </div>
            <div class="col-md-12">
                <table width="100%" border="1">
                    <tr>
                        <td colspan="3" width="70%">For payment of:</td>
                        <td width="5%"></td>
                        <td style="text-align: center">AMOUNT</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 50px;"><?= $model->particular ?></td>
                        <td>P</td>
                        <td class="rightni"><?= number_format($model->amount,2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;">TOTAL</td>
                        <td>P</td>
                        <td class="rightni"><span id="totalhere"><?= number_format($model->amount,2) ?></span></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">ACCOUNT TITLE</td>
                        <td style="text-align: center;">DEBIT</td>
                        <td></td>
                        <td style="text-align: center;">CREDIT</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;padding: 20px;"></td>
                        <td style="text-align: center;"><?= $model->debit0->object_code ?></td>
                        <td></td>
                        <td style="text-align: center;"><?= $model->credit0->object_code ?></td>
                    </tr>
                    <tr>
                        <td rowspan="3">
                            <label class="form-label">Prepared By:</label><br><br>
                            <center><?= $model->prepared?$model->prepared->nameandposition:'' ?></center>
                        </td>
                        <td rowspan="3">
                            <label class="form-label">Approved By:</label><br><br>
                            <center><?= $model->approved?$model->approved->nameandposition:'' ?></center>
                        </td>
                        <td style="text-align: center;">Form of payment</td>
                        <td colspan="2" rowspan="3">
                            <label class="form-label">Received By:</label><br><br>
                            <center><?= $model->received_by ?></center>
                        </td>
                    </tr>
                    <tr><td>Cash</td></tr>
                    <tr><td>Check</td></tr>
                </table>
                <br>
            </div>
        </div>
    </div>
</div>