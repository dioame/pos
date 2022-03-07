<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\JevEntries;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevTrackingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Journal Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-tracking-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            'jev',
            'remarks',
            [
                'label'=>'Debit',
                'format'=>'raw',
                'value'=>function($model){
                    $string = '';
                    $entries = JevEntries::find()->where(['jev'=>$model->jev,'type'=>'debit'])->all();
                    $string='<table width="100%" style="font-size:0.8em;">';
                    foreach ($entries as $key) {
                        $string.= '<tr>';
                        $string.= '<td>'.$key->accountingCode->object_code.'</td>';
                        $string.= '<td>&nbsp;</td>';
                        $string.= '<td class="rightni">'.number_format($key->amount,2).'</td>';
                        $string.= '</tr>';
                    }
                    $string .='</table>';
                    return $string;
                }
            ],
            [
                'label'=>'Credit',
                'format'=>'raw',
                'value'=>function($model){
                    $string = '';
                    $entries = JevEntries::find()->where(['jev'=>$model->jev,'type'=>'credit'])->all();
                    $string='<table width="100%" style="font-size:0.8em;">';
                    foreach ($entries as $key) {
                        $string.= '<tr>';
                        $string.= '<td>'.$key->accountingCode->object_code.'</td>';
                        $string.= '<td>&nbsp;</td>';
                        $string.= '<td class="rightni">'.number_format($key->amount,2).'</td>';
                        $string.= '</tr>';
                    }
                    $string .='</table>';
                    return $string;
                }
            ],
            'date_posted',
            //'source',

            ['class' => 'kartik\grid\ActionColumn'],
            /*[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visibleButtons'=>[
                    'delete'=>function($model,$key,$index){
                        if(Yii::$app->user->can('treasurer-only')&&($model->source=='others'||$model->source=='closing entry')){
                            return true;
                        }else{
                            return false;
                        }
                    },
                    'update'=>function($model,$key,$index){
                        if(Yii::$app->user->can('treasurer-only')&&($model->source=='others'||$model->source=='closing entry')){
                            return true;
                        }else{
                            return false;
                        }
                    }
                ],
            ],*/
        ],
        'toolbar' =>  [
                    ['content'=>
                        Html::a('Create Journal Entry', ['create'], ['class' => 'btn btn-success'])
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
</div>
