<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UacsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Chart of Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uacs-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'classification',
            'sub_class',
            'grouping',
            'object_code',
            'uacs',
            'payment_account',
            'status',
            [
                'attribute' => 'isEnabled',
                'value' => function($model){
                    return $model->isEnabled==1?'Yes':'No';
                }
            ],
            [
                'class' => '\kartik\grid\ActionColumn',
                'template'=>'{view}{edit}{delete}',
                'buttons' => [
                    'edit' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '', ['value' => Url::to(['uacs/update','id'=>$model->uacs]), 'title' => 'Edit Chart of Accounts', 'class' => 'showCreateModal']);
                      }
                ]
            ]
        ],
        'toolbar' =>  [
                ['content'=>
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', ['value' => Url::to(['uacs/create']), 'title' => 'Add New Chart of Accounts', 'class' => 'showCreateModal btn btn-success'])
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
          $(document).on('click', '.showCreateModal', function(){
                $('#genericmodal').modal('show')
                        .find('#modal-body')
                        .load($(this).attr('value'));
                document.getElementById('modal-title').innerHTML = '<h4>' + $(this).attr('title') + '</h4>';
        });
            
    });
</script>
