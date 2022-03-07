<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Expenses;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpensesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Disbursement Journal';
$this->params['breadcrumbs'][] = $this->title;

/*if(isset($_GET['date_from'])){
    $datefrom = date('Y-m-d',strtotime($_GET['date_from']));
}else{
    $datefrom = '2018-01-01';
}

if(isset($_GET['date_to'])){
    $dateto = date('Y-m-d',strtotime($_GET['date_to']));
}else{
    $dateto = date('Y-m-d');
}

$i=1;

$entries = JevEntries::find()->joinWith('jev0')->where(['AND',['accounting_code'=>10101000],['type'=>'credit','source'=>'expenses']])->andWhere(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->all();*/

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
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],

        //'id',
        'date_posted',
        'dv_number',
        [
            'attribute'=>'payee',
            'value'=>function($model){
                if($model->payee)
                return $model->payee0->supplier_name;
            },
        ],
        'particular',
        [
            'attribute'=>'amount',
            'format'=>['decimal',2]
        ],
        //'type',
        //'debit',
        //'credit',
        //'prepared_by',
        //'requested_by',
        //'approved_by',
        //'received_by',
        'jev',

        ['class' => 'kartik\grid\ActionColumn'],
    ],
    'toolbar' =>  [
                ['content'=>
                    ''
                ],
                '{export}',
                '{toggleData}'
            ],
            'pjax' => false,
            'bordered' => true,
            'striped' => false,
            'condensed' => false,
            'responsive' => true,
            'hover' => true,
            'showPageSummary' => false,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT
            ],
    ]); ?>

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
