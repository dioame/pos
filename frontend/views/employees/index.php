<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EmployeesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employees-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'firstname',
            'lastname',
            [
                'attribute'=>'Username',
                'format'=>'raw',
                'hAlign'=>'center',
                'value'=>function($model){
                    if($model->user)
                    return $model->user->username.'<br>'.Html::a('<small><i class="glyphicon glyphicon-refresh"></i> Reset Password</small>',['employees/resetpasswordadmin','id'=>$model->id],['class' => 'btn btn-danger btn-xs']);
                }
            ],
            'date_started',
            'address',
            //'contact_number',
            //'email:email',
            //'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'toolbar' =>  [
                ['content'=>
                    Html::button('<i class="glyphicon glyphicon-plus"></i> Add New', ['value' => Url::to(['employees/create']), 'title' => 'Add New Employee', 'class' => 'showModal btn btn-success']).'</div><div class="btn-group">'.Html::a('<i class="glyphicon glyphicon-user"></i> Manage Roles',['auth-assignment/index'],['class' => 'btn btn-primary'])
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