<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\select2\Select2;
use app\models\Members;
use app\models\Settings;
use app\models\JevEntries;
use app\models\Capitals;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CapitalsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Capital Contributions Journal';
$this->params['breadcrumbs'][] = $this->title;

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

$model = Capitals::find()->where(['>=','date(date_posted)',$datefrom])->andWhere(['<=','date(date_posted)',$dateto])->all();


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

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute'=>'date_posted',
                'value'=>function($model){
                    return date('F j, Y',strtotime($model->jev0->date_posted));
                }
            ],
            [
                'attribute'=>'AR NO',
                'value'=>function($model){
                  if($model = Capitals::find()->where(['jev'=>$model->jev])->one())
                    return $model->arNo;
                }
            ],
            [
                'attribute'=>'membersId',
                'value'=>function($model){
                    if($model = Capitals::find()->where(['jev'=>$model->jev])->one())
                    return $model->members->firstname.' '.$model->members->lastname;
                }
            ],
            [
                'label'=>'Cash',
                'value'=>function($model){
                    if($model = Capitals::find()->where(['jev'=>$model->jev])->one())
                    if($model->type=='cash'){
                        return $model->amount;
                    }
                },
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'Others',
                'value'=>function($model){
                    if($model = Capitals::find()->where(['jev'=>$model->jev])->one())
                    if($model->type=='others'){
                        return $model->amount;
                    }
                },
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'Total',
                'value'=>function($model){
                    if($model = Capitals::find()->where(['jev'=>$model->jev])->one())
                    return $model->amount;
                },
                'format'=>['decimal',2],
                'pageSummary'=>true,
                'pageSummaryFunc'=>GridView::F_SUM
            ],
            [
                'label'=>'JEV',
                'value'=>function($model){
                    return $model->jev;
                }
            ],
			/*[
				'attribute'=>'membersId',
				'value'=>function($model){

					return $model->members->firstname.' '.$model->members->lastname;
				}
			],
			[
				'label'=>'Cash',
				'value'=>function($model){
					if($model->type=='cash'){
						return $model->amount;
					}
				},
				'format'=>['decimal',2],
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM
			],
			[
				'label'=>'Others',
				'value'=>function($model){
					if($model->type=='others'){
						return $model->amount;
					}
				},
				'format'=>['decimal',2],
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM
			],
			[
				'label'=>'Total',
				'value'=>function($model){
					return $model->amount;
				},
				'format'=>['decimal',2],
				'pageSummary'=>true,
				'pageSummaryFunc'=>GridView::F_SUM
			],
			[
				'label'=>'JEV',
				'value'=>function($model){
					return $model->jev;
				}
			],*/
			[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{delete}',
                'visibleButtons'=>[
                    'delete'=>function($model,$key,$index){
                        return Yii::$app->user->can('treasurer-only')?true:false;
                    }
                ]
            ],
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
                'showPageSummary' => true,
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
        });
            
    });
</script>