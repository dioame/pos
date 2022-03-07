<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AuthAssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Auth Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-assignment-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'user_id',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>\yii\helpers\ArrayHelper::map(User::find()->all(), 'id', 'fulllist'),
                'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => '...'],
                'value'=>function($model){
                    return $model->user->employee->firstname.' '.$model->user->employee->lastname;
                }
            ],
            'item_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                ['content'=>
                    Html::button('<i class="glyphicon glyphicon-plus"></i> Assign New', ['value' => Url::to(['auth-assignment/create']), 'title' => 'Assign New Authentication', 'class' => 'showModal btn btn-success']).'</div><div class="btn-group">'
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
            'showPageSummary' => false,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT
            ],
    ]); ?>
</div>
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