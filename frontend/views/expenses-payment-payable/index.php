<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Settings;
use app\models\Expenses;
use app\models\PaymentsReceivable;
use app\models\ExpensesPaymentPayable;

$this->title = 'Payables';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
    $t1 = strtotime($a[0]);
    $t2 = strtotime($b[0]);
    return $t1-$t2;
}

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
        <table id="exportTable" width="100%" border="0" style="border-bottom: 1px solid black;">
            <tr>
                <td class="center" colspan="14" style="border-left: 0px;border-right: 0px;padding: 0px;">
                    
                        <h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
                        <?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br><br>
                        <h3 style="margin:0px;">Accounts Payable Ledger</h3>
                        <br><br>

                </td>
            </tr>

            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Year</th>
                <th rowspan="2">Month</th>
                <th rowspan="2">Day</th>
                <th rowspan="2">Transaction#</th>
                <th rowspan="2">Payee Name</th>
                <th colspan="2">Payable</th>
                <th colspan="2">Payments</th>
                <th rowspan="2">Cumulative Balance</th>
                <th colspan="2">Bookkeeper's Note</th>
                <th rowspan="2">Action Column</th>
            </tr>
            <tr>
                <th>Particulars</th>
                <th>Amount Payable</th>
                <th>Ref#</th>
                <th>Amount</th>
                <th>JEV#</th>
                <th>Remarks</th>
            </tr>

            <?php

                $all= [];

                $payables = Expenses::find()->where(['>','balance',0])->andWhere(['!=','jev',""])->all();

                foreach ($payables as $payables) {
                    array_push($all,[$payables->date,$payables->id,'payables']);
                }

                $payments = ExpensesPaymentPayable::find()->all();

                foreach ($payments as $payments) {
                    array_push($all,[$payments->transaction_date,$payments->id,'payments']);
                }

                usort($all,"date_compare");

                $i=1;
                $cumulativebal=0;
                foreach ($all as $key => $val) {

                    switch ($val[2]) {
                        case 'payables':
                            $temppayable = Expenses::findOne($val[1]);

                            $cumulativebal += $temppayable->balance;

                            echo '<tr>';
                            echo '<td>'.$i++.'</td>';
                            echo '<td>'.date('Y',strtotime($val[0])).'</td>';
                            echo '<td>'.date('m',strtotime($val[0])).'</td>';
                            echo '<td>'.date('d',strtotime($val[0])).'</td>';
                            echo '<td>'.date('Y-m-',strtotime($temppayable->date)).$temppayable->id.'</td>';
                            echo '<td>'.$temppayable->payee.'</td>';
                            echo '<td>'.$temppayable->type0->name.'</td>';
                            echo '<td class="rightni">'.number_format($temppayable->balance,2).'</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td class="rightni">'.number_format($cumulativebal,2).'</td>';
                            echo '<td>'.$temppayable->jev.'</td>';
                            echo '<td></td>';

                            $temppayments = ExpensesPaymentPayable::find()->where(['expense_id'=>$temppayable->id])->sum('amount_paid');

                            echo  $temppayments<$temppayable->balance?'<td><a value="'.Url::to(['expenses-payment-payable/create','id'=>$temppayable->id]).'" class="showModal" title="Add Payments"><small class="label label-success">Add Payment</small></a></td>':'<td></td>';

                            echo '</tr>';
                            break;

                        case 'payments':
                            $temp = ExpensesPaymentPayable::findOne($val[1]);
                            $cumulativebal -= $temp->amount_paid;
                            echo '<tr>';
                            echo '<td>'.$i++.'</td>';
                            echo '<td>'.date('Y',strtotime($val[0])).'</td>';
                            echo '<td>'.date('m',strtotime($val[0])).'</td>';
                            echo '<td>'.date('d',strtotime($val[0])).'</td>';
                            echo '<td>'.date('Y-m-',strtotime($temp->expense->date)).$temp->expense->id.'</td>';
                            echo '<td>'.$temp->expense->payee.'</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td>'.date('Y-m-',strtotime($temp->transaction_date)).$temp->id.'</td>';
                            echo '<td class="rightni">'.number_format($temp->amount_paid,2).'</td>';
                            echo '<td class="rightni">'.number_format($cumulativebal,2).'</td>';

                            echo '<td>';
                            if(Yii::$app->user->can('bookkeeper-only')){
                                echo $temp->jev!=null?'<a value="'.Url::to(['expenses-payment-payable/updatejev','id'=>$temp->id]).'" class="showModal" title="Update JEV Details">'.$temp->jev.'</a>':'<a value="'.Url::to(['expenses-payment-payable/updatejev','id'=>$temp->id]).'" class="showModal" title="Update JEV Details" style="color:red;"><i>(not set)</i><a>';
                            }else{
                                echo $temp->jev;
                            }
                            echo '</td>';
                            echo '<td></td>';
                            echo '<td>
                                <a value="'.Url::to(['expenses-payment-payable/update','id'=>$temp->id]).'" class="showModal" title="Update"><small class="label label-warning">Update</small></a>'.
                                Html::a('<small class="label label-danger">Delete</', ['delete', 'id' => $temp->id], [
                                            'class' => '',
                                            'data' => [
                                                'confirm' => 'Are you sure you want to delete this item?',
                                                'method' => 'post',
                                            ],
                                        ]).'

                                </td>';

                            echo '</tr>';
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }
            ?>


        </table>
    </div>
</div>
<script type="text/javascript">
    $(function(){
          $(document).on('click', '.showModal', function(){
                $('#genericmodal').modal('show')
                        .find('#modal-body')
                        .load($(this).attr('value'));
                document.getElementById('modal-title').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
                setTimeout( function() { 
                  jQuery( '#expenses-jev').focus();
                }, 500 );
        });
            
    });
</script>