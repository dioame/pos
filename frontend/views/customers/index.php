<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CustomersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customers-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'firstname',
            'lastname',
            'contactNo',
            'address',

            ['class' => 'kartik\grid\ActionColumn'],

        ],
        'toolbar' =>  [
                ['content'=>
                    Html::button('<i class="glyphicon glyphicon-plus"></i>', ['value' => Url::to(['customers/create']), 'title' => 'Add New Member', 'class' => 'showModal btn btn-success'])
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