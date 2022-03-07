<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\StockCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Production Report';
$this->params['breadcrumbs'][] = $this->title;

function calculatehours($d1,$d2){
    $date1 = new DateTime($d1);
    $date2 = new DateTime($d2);

    $diff = $date2->diff($date1);

    $hours = $diff->h;
    $hours = $hours + ($diff->days*24);

    return $hours;
}
?>
<div class="stock-card-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

   <!--  <p>
        <?= Html::a('Create Stock Card', ['create'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            //'id',
            [
                'label'=>'Particulars',
                'value'=>function($model){
                    return 'Daily production of '.$model->product->product_name.' - '.$model->remarks;
                }
            ],
            [
                'label'=>'In',
                'value'=>function($model){
                    return $model->added;
                }
            ],
            [
                'label'=>'Weight per blocks (kg)',
                'value'=>function($model){
                    return 20;
                }
            ],
            [
                'label'=>'Total weight per block (kg)',
                'value'=>function($model){
                    return $model->added*20;
                }
            ],
            [
                'label'=>'Started',
                'value'=>function($model){
                    return date('M j, Y g:i:s a',strtotime($model->date));
                }
            ],
            [
                'label'=>'Finished',
                'value'=>function($model){
                    return date('M j, Y g:i:s a',strtotime($model->finished));
                }
            ],
            [
                'label'=>'Duration',
                'value'=>function($model){
                    return calculatehours($model->date,$model->finished);
                }
            ],
            [
                'label'=>'Prepared by: Caretaker',
                'value'=>function($model){
                    return '';
                }
            ],
            [
                'label'=>'Checked by: Manager',
                'value'=>function($model){
                    return '';
                }
            ],
            //'remarks',
            //'total',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                ['content'=>
                    ''
                ],
                '{export}',
                '{toggleData}'
            ],
            'pjax' => true,
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
    <?php Pjax::end(); ?>
</div>
