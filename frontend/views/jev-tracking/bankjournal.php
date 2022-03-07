<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Expenses;
use app\models\ExpensesPayments;
use app\models\PettyCash;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpensesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash in Bank Journal';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
    $t1 = strtotime($a[0]);
    $t2 = strtotime($b[0]);
    return $t1-$t2;
}

if(isset($_GET['date_from'])){
    $datefrom = date('Y-m-d',strtotime($_GET['date_from']));
}else{
    $datefrom = date('Y-m-01');
}

if(isset($_GET['date_to'])){
    $dateto = date('Y-m-d',strtotime($_GET['date_to']));
}else{
    $dateto = date('Y-m-t');
}


$entries = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10102010])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->all();

$balance = JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10102010,'type'=>'debit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount')-JevEntries::find()->joinWith('jev0')->where(['accounting_code'=>10102010,'type'=>'credit'])->andWhere(['<','date(date_posted)',$datefrom])->sum('amount');

?>
<style type="text/css">
    a{
        cursor: pointer;
    }
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
        <form action="" method="GET">
        <input type="date" name="date_from" class="" value="<?= $datefrom ?>" />
        <input type="date" name="date_to" class="" value="<?= $dateto ?>" />
        <button type="submit">Submit</button> <input type="button" onclick="tableToExcel('exportTable', 'Cash Journal - <?= date("m/d/Y") ?>')" value="Export to Excel">
        </form>
        <br><br>
        <table id="exportTable" width="100%" border="0" style="border-bottom: 1px solid black;">
            <tr>
                <td class="center" colspan="12" style="border-left: 0px;border-right: 0px;padding: 0px;">
                        <h3 style="margin: 0px;padding: 0px;"><b><?= Settings::find()->where(['attribute'=>'name'])->one()->value ?></b></h3>
                        <?= Settings::find()->where(['attribute'=>'address'])->one()->value ?><br>
                        For the Period: <?= date('F j - ',strtotime($datefrom)).date('F j, 2018',strtotime($dateto)) ?><br><br>
                    <h3 style="margin:0px;"><?= $this->title ?></h3><br><br>
                </td>
            </tr>
            <tr>
                <th>Date</th>
                <th width="40%">Particulars</th>
                <th width="10%">DEPOSITS</th>
                <th width="10%">WITHDRAWALS</th>
                <th>Balance</th>
                <th>JEV#</th>
                <th>Remarks</th>
            </tr>

            <tr>
                <td><?= date('Y-m-d',strtotime($datefrom)) ?></td>
                <td><b><i>Add previous balance...</i></b></td>
                <td></td>
                <td></td>
                <td class="rightni"><?= number_format($balance,2) ?></td> 
                <td></td>
                <td></td>
            </tr>

            <?php
                foreach ($entries as $key) {
                    echo '<tr>';
                    echo '<td>'.date('Y-m-d',strtotime($key->jev0->date_posted)).'</td>';
                    echo '<td>'.$key->jev0->remarks.'</td>';
                    if($key->type=='debit'){
                        echo '<td class="rightni">'.number_format($key->amount,2).'</td><td></td>';
                        $balance += $key->amount;
                    }else{
                        echo '<td></td><td class="rightni">'.number_format($key->amount,2).'</td>';
                        $balance -= $key->amount;
                    }
                    echo '<td class="rightni">'.number_format($balance,2).'</td>';
                    echo '<td>'.$key->jev.'</td>';
                    echo '<td></td>';
                    echo '</tr>';
                }
            ?>
<?php
        $all=[];
        $i=0;
        $balance = 0;

        $prevcashin = Expenses::find()->select('date,sum(amount_paid) as amount')->where(['<','date(date)',$datefrom])->andWhere(['type'=>8])->sum('amount');

        $prevcashout = ExpensesPayments::find()->joinWith('expense')->where(['<','date(date_recorded)',$datefrom])->andWhere(['expenses_payments.type'=>'petty cash fund'])->sum('expenses_payments.amount_paid');

        $balance = $prevcashin-$prevcashout;

        $previousbalance = $balance;

        //capitals
        $cashin = Expenses::find()->where(['>=','date(date)',$datefrom])->andWhere(['<=','date(date)',$dateto])->andWhere(['type'=>8])->all();

        $cashout = ExpensesPayments::find()->joinWith('expense')->where(['>=','date(date_recorded)',$datefrom])->andWhere(['<=','date(date_recorded)',$dateto])->andWhere(['expenses_payments.type'=>'petty cash fund'])->all();

     


        foreach ($cashin as $cashin) {
            array_push($all,[$cashin->date,$cashin->id,'cashin']);
        }

        foreach ($cashout as $cashout) {
            array_push($all,[$cashout->expense->date,$cashout->id,'cashout']);
        }

        usort($all,"date_compare");


        

        $particulars = '';
        $ref = '';
        $in = '';
        $out = '';
        $bal = 0;
        

        ?>
                

                <?php
                $i++;
                foreach ($all as $key => $val) {
                    $jev = '';
                    $i++;
                    echo '<tr>';
                    switch ($val[2]) {
                        case 'cashin':
                            $tempexpense = Expenses::findOne($val[1]);
                            $particulars = $tempexpense->remarks;
                            $ref = 'DV#'.date('Y-m-',strtotime($tempexpense->date)).$tempexpense->id;
                            $in = number_format($tempexpense->amount_paid,2);
                            $out = '';
                            $balance+=$tempexpense->amount_paid;
                            $bal = number_format($balance,2);
                            $jev = $tempexpense->jev;
                            break;
                        case 'cashout':
                            $tempexpense = ExpensesPayments::findOne($val[1]);
                            $particulars = $tempexpense->expense->remarks;
                            $ref = 'DV#'.date('Y-m-',strtotime($tempexpense->date_recorded)).$tempexpense->expense->id;
                            $in = '';
                            $out = number_format($tempexpense->amount_paid,2);
                            $balance-=$tempexpense->amount_paid;
                            $bal = number_format($balance,2);
                            $jev = $tempexpense->expense->jev;
                            break;
                        default:break;
                    }
                    if(Yii::$app->user->can('bookkeeper-only')){
                        if($val[2]=='cashout'){

                            echo '<td><a class="showModal" value="'.Url::to(['expenses/update','id'=>$tempexpense->expense->id]).'" title="Edit">'.date('Y-m-d',strtotime($val[0])).'</a></td>';

                        }else{
                            echo '<td>'.date('Y-m-d',strtotime($val[0])).'</td>';
                        }
                    }else{
                        echo '<td>'.date('Y-m-d',strtotime($val[0])).'</td>';
                    }
                    echo '<td>'.$particulars.'</td>';
                    echo '<td>'.$ref.'</td>';
                    echo '<td class="rightni">'.$in.'</td>';
                    echo '<td class="rightni">'.$out.'</td>';
                    echo '<td class="rightni">'.$bal.'</td>';
                    if(Yii::$app->user->can('bookkeeper-only')){
                        if($val[2]=='cashout'){
                            echo '<td><a id="'.$tempexpense->expense->id.'" value="'.Url::to(['expenses/updatejev','id'=>$tempexpense->expense->id]).'" class="showModal" title="Update JEV Details"><i>'.($jev!=null?$jev:'(not set)').'</i><a></td>';
                        }else{
                            echo '<td>'.$jev.'</td>';
                        }
                    }else{
                        echo '<td>'.$jev.'</td>';
                    }
                    echo '<td></td>';
                    echo '</tr>';
                }
                /*foreach ($stocks as $stocks) {
                    array_push($all,[$stocks->date,$stocks->id,'stocks']);
                }*/
        ?>
    </table>
    </div>
</div>
<script type="text/javascript">
var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    window.location.href = uri + base64(format(template, ctx))
  }
})()

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