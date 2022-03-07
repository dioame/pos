<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Uacs;
use app\models\Ppe;

/* @var $this yii\web\View */
/* @var $model app\models\PpeDepreciation */
/* @var $form yii\widgets\ActiveForm */
$ppeModel = Ppe::findOne($_GET['ppeID']);
$model->date_from = date('Y-m-d',strtotime(date('Y'.'-01-01')));
$model->date_to = date('Y-m-d',strtotime(date('Y'.'-12-31')));
?>

<div class="ppe-depreciation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ppeID')->hiddenInput(['value'=>$_GET['ppeID']])->label(false) ?>

    <div class="col-md-3">
        <?= $form->field($model, 'date_from')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
                'pluginEvents' => [
                    "changeDate" => "function(e)
                    {
                        calculate();
                    }",
                ]
            ])
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'date_to')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
                'pluginEvents' => [
                    "changeDate" => "function(e)
                    {
                        calculate();
                    }",
                ]
            ])
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'date_posted')->widget(DatePicker::classname(), [
                'type' => DatePicker::TYPE_INPUT,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-m-d',
                    'todayHighlight' => true
                ],
            ])
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'amount')->textInput(['type'=>'number','step'=>0.01]) ?>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    

    

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    function calculate(){
        var date1 = new Date($("#ppedepreciation-date_from").val());
        var date2 = new Date($("#ppedepreciation-date_to").val());
        var timeDiff = Math.abs(date2.getTime() - date1.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
        $("#ppedepreciation-amount").val(diffDays*<?= ((($ppeModel->quantity*$ppeModel->unit_cost)/$ppeModel->eul)/(date("z", strtotime(date('Y').'-12-31')) + 1)) ?>);
    }
</script>