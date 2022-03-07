<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\OrArTracking;
use app\models\Settings;
use app\models\Capitals;
use app\models\JevEntries;
use app\models\Sales;
use app\models\PaymentsReceivable;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrArTrackingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'OR/AR Summary';
$this->params['breadcrumbs'][] = $this->title;

function date_compare($a,$b){
    $t1 = strtotime($a[0]);
    $t2 = strtotime($b[0]);
    return $t1-$t2;
}

if(isset($_GET['date_from'])){
    $datefrom = date('Y-m-d',strtotime($_GET['date_from']));
}else{
    $datefrom = '2018-01-01';
}

if(isset($_GET['date_to'])){
    $dateto = date('Y-m-d',strtotime($_GET['date_to']));
}else{
    $dateto = date('Y-m-d');
}

$entries = JevEntries::find()->joinWith('jev0')->where(['OR',['accounting_code'=>30101030,'type'=>'credit'],['accounting_code'=>40202160,'type'=>'credit'],['accounting_code'=>10301010,'type'=>'credit']])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->groupBy('jev_entries.jev')->orderBy(['date_posted'=>SORT_ASC,'jev_entries.id'=>SORT_ASC])->all();


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
                    <h3 style="margin:0px;">OR/AR Summary</h3>
                    <br><br>

            </td>
        </tr>
        <tr>
            <th>Date</th>
            <th>Payor</th>
            <th>Particular</th>
            <th>Amount</th>
            <th>OR/AR No.</th>
        </tr>
    <?php
        foreach ($entries as $key) {
            echo '<tr>';

            switch ($key->accounting_code) {
                case 30101030:
                    $model = Capitals::find()->where(['jev'=>$key->jev])->one();
                    $payor = $model->members->lastname.', '.$model->members->firstname;
                    $date = $key->jev0->date_posted;
                    $particular = $key->jev0->remarks;
                    $amount = $key->amount;
                    $or = '<a id="'.$model->id.'" value="'.Url::to(['capitals/editor','id'=>$model->id]).'" class="showModal" title="Update AR">AR-'.$model->arNo.'</a>';
                    break;

                case 40202160:
                    $model = Sales::find()->where(['jev'=>$key->jev])->one();
                    $payor = $model->customer->lastname.', '.$model->customer->firstname;
                    $date = $key->jev0->date_posted;
                    $particular = $key->jev0->remarks;
                    $amount = $model->amount_paid;
                    $or = '<a id="'.$model->id.'" value="'.Url::to(['or-ar-tracking/editor','id'=>$model->or->id]).'" class="showModal" title="Update OR">AR-'.$model->or->tracking.'</a>';
                    break;

                case 10301010:
                    $model = PaymentsReceivable::find()->where(['jev'=>$key->jev])->one();
                    $payor = $model->sales->customer->lastname.', '.$model->sales->customer->firstname;
                    $date = $key->jev0->date_posted;
                    $particular = $key->jev0->remarks;
                    $amount = $key->amount;
                    $or = '<a id="'.$model->or->id.'" value="'.Url::to(['or-ar-tracking/editor','id'=>$model->or->id]).'" class="showModal" title="Update OR">AR-'.$model->or->tracking.'</a>';
                    break;

                
                default:
                    $date = "";
                    $payor = "";
                    $particular = "";
                    $amount = 0;
                    $or = "";
                    break;
            }

            echo '<td>'.date('F j, Y',strtotime($date)).'</td>';
            echo '<td>'.$payor.'</td>';
            echo '<td>'.$particular.'</td>';
            echo '<td class="rightni">'.number_format($amount,2).'</td>';
            echo '<td class="rightni">'.$or.'</td>';
            echo '</tr>';
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
