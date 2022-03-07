<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Settings;
use app\models\PayrollDeductions;
use app\models\PayslipDeductionTypes;
use app\models\Employees;

/* @var $this yii\web\View */
/* @var $model app\models\Payroll */

$this->title = 'Payslip';
$this->params['breadcrumbs'][] = ['label' => 'Payrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    td, th{
        padding: 5px;
    }
    hr{
        border-color:black;
        margin: 2px;
    }
</style>
<div class="row">
    <div class="col-md-offset-3 col-md-6 col-md-offset-3" style="border: 1px solid black;padding:10px;">
        <center>
            <h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
            <?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br>
            EMPLOYEE'S PAYSLIP
        </center>
        <br>
        <table width="100%">
            <tr>
                <td>ID No.:</td>
                <td>For the Period </td>
                <td width="20%"><?= date('M j, Y',strtotime($model->date_from)) ?></td>
                <td>to</td>
                <td width="20%"><?= date('M j, Y',strtotime($model->date_to)) ?></td>
            </tr>
        </table>
        <table width="100%" style="border: 2px solid black">
            <tr>
                <th class="center">Surname</th>
                <th class="center">Firstname</th>
                <th class="center">Position</th>
                <th class="center">Hourly Rate</th>
            </tr>
            <tr>
                <td class="center"><?= $model->emp->lastname ?></td>
                <td class="center"><?= $model->emp->firstname ?></td>
                <td class="center"><?= $model->emp->position ?></td>
                <td class="center"><?= number_format($model->hourly_rate,2) ?></td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <th>Gross:</th>
                <td></td>
                <th class="rightni"><?= number_format($model->number_of_hours*$model->hourly_rate,2) ?></th>
            </tr>
            <tr>
                <th colspan="3">Deductions:</th>
            </tr>
            <?php
                $total_deduction = 0;
                $deductions = PayslipDeductionTypes::find()->all();
                foreach ($deductions as $key) {
                    if(!$deduct = PayrollDeductions::find()->where(['pID'=>$model->id,'dID'=>$key->id])->one()->amount){
                        $deduct = 0;
                    }
                    $total_deduction += $deduct;
                    echo '<tr>';
                    echo '<td style="padding-left:50px;">'.$key->type.'</td>';
                    echo '<td class="rightni">'.number_format($deduct,2).'</td>';
                    echo '<td></td>';
                    echo '</tr>';
                }
            ?>
            <tr>
                <th>Total Deductions</th>
                <th></th>
                <th class="rightni">(<?= number_format($total_deduction,2) ?>)</th>
            </tr>
            <tr>
                <th>NET TAKE HOME PAY</th>
                <th></th>
                <th class="rightni"><hr><?= number_format(($model->number_of_hours*$model->hourly_rate)-$total_deduction,2) ?><hr><hr></th>
            </tr>
        </table>
        <?php
            if(!$tempmodel=Employees::find()->where(['like','position','treasurer'])->one()){
                $treasurer = "Treasurer not yet set";
            }else{
                $treasurer = $tempmodel->firstname.' '.$tempmodel->lastname;
            }
        ?>
        <center>
        <p style="padding-top: 30px;">Certified true and correct</p>
        <p style="padding-top: 20px;"><b><?= $treasurer ?></b><br>Treasurer</p><br>
        <p style="padding: 0px 90px;"><small>Please report erroneous entries to the Treasurer within 5 days upon receipt of this payslip</small></p>
        </center>
    </div>
</div>
