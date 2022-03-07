<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SuppliersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="suppliers-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'supplier_name',
            'tin',
            'contact_number',
            'email_address:email',
            [
                'class' => '\kartik\grid\ActionColumn',
                'template'=>'{view}{edit}{delete}',
                'buttons' => [
                    'edit' => function ($url, $model) {     
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', '', ['value' => Url::to(['suppliers/update','id'=>$model->id]), 'title' => 'Edit Supplier', 'class' => 'showCreateModal']);
                      }
                ]
            ]
        ],
        'toolbar' =>  [
                ['content'=>
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', ['value' => Url::to(['suppliers/create']), 'title' => 'Add New Supplier', 'class' => 'showCreateModal btn btn-success'])
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
