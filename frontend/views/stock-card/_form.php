<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Products;

/* @var $this yii\web\View */
/* @var $model app\models\StockCard */
/* @var $form yii\widgets\ActiveForm */

if($model->id){
    $hoursfrom=date('g',strtotime($model->date));
    $hoursto=date('g',strtotime($model->finished));
    $minutesfrom=date('i',strtotime($model->date));
    $minutesto=date('i',strtotime($model->finished));
    $model->date = date('Y-m-d',strtotime($model->date));
    $model->finished = date('Y-m-d',strtotime($model->finished));
}

    $medianfrom=date('a',strtotime($model->date));
    $medianto=date('a',strtotime($model->finished));
?>

<div class="row">
	<div class="col-md-offset-3 col-md-6 col-md-offset-3">
		<center><h1><?= Html::encode($this->title) ?></h1></center><hr>

    <?php $form = ActiveForm::begin(); ?>
    <?php
        echo $form->field($model, 'sku')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Products::find()->all(), 'sku', 'product_name'),
            'options' => ['placeholder' => 'Select product ...'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]);

    ?>

    <?= $form->field($model, 'remarks')->dropDownList([ 'Batch 1' => 'Batch 1', 'Batch 2' => 'Batch 2', 'Batch 3' => 'Batch 3'], ['options' => ['Batch 1' => ['Selected'=>'selected']]])->label('Batch') ?>


    <?= $form->field($model, 'added')->textInput(['placeholder'=>'Quantity'])->label('Quantity') ?>

    <div class="row">
        <div class="col-md-5">
        <?= $form->field($model, 'date')->widget(DatePicker::classname(), [
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-m-d',
                'todayHighlight' => true
            ],
        ]) ?>
        </div>
        <div class="col-md-2 no-padding">
            <label class="form-label">Time</label>
                <?php
                	$data=[];
                	for($i=1;$i<=12;$i++){
                	    if($i<10){
                	    	$data[$i]=sprintf("%02d", $i);
                	    }else{
                	    	$data[$i]=$i;
                	    }
                	}
                	echo Select2::widget([
                	    'name' => 'hoursfrom',
                	    'value' => $model->id?$hoursfrom:1,
                	    'data' => $data,
                	]);
                ?>
        </div>
        <div class="col-md-2 no-padding">
            <label class="form-label">&nbsp;</label>
                <?php
                	$data=[];
                    for($i=0;$i<=59;$i++){
                        if($i<10){
                            $data[$i]=sprintf("%02d", $i);
                        }else{
                            $data[$i]=$i;
                        }
                    }
                    echo Select2::widget([
                	    'name' => 'minutesfrom',
                	    'value' => $model->id?$minutesfrom:0,
                	    'data' => $data,
                	]);
                ?>
        </div>
        <div class="col-md-3" style="padding-right: 10px;">
            <label class="form-label">&nbsp;</label>
            <select class="form-control" name="medianfrom">
                <option value="am" <?= $medianfrom=='am'?'selected':''?> >AM</option>
                <option value="pm" <?= $medianfrom=='pm'?'selected':''?> >PM</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
        <?= $form->field($model, 'finished')->widget(DatePicker::classname(), [
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-m-d',
                'todayHighlight' => true
            ],
        ]) ?>
        </div>
        <div class="col-md-2 no-padding">
            <label class="form-label">Time</label>
                <?php
                	$data=[];
                	for($i=1;$i<=12;$i++){
                	    if($i<10){
                	    	$data[$i]=sprintf("%02d", $i);
                	    }else{
                	    	$data[$i]=$i;
                	    }
                	}
                	echo Select2::widget([
                	    'name' => 'hoursto',
                	    'value' => $model->id?$hoursto:1,
                	    'data' => $data,
                	]);
                ?>
        </div>
        <div class="col-md-2 no-padding">
            <label class="form-label">&nbsp;</label>
                <?php
                	$data=[];
                    for($i=0;$i<=59;$i++){
                        if($i<10){
                            $data[$i]=sprintf("%02d", $i);
                        }else{
                            $data[$i]=$i;
                        }
                    }
                    echo Select2::widget([
                	    'name' => 'minutesto',
                	    'value' => $model->id?$minutesto:0,
                	    'data' => $data,
                	]);
                ?>
        </div>
        <div class="col-md-3" style="padding-right: 10px;">
            <label class="form-label">&nbsp;</label>
            <select class="form-control" name="medianto">
                <option value="am" <?= $medianto=='am'?'selected':''?> >AM</option>
                <option value="pm" <?= $medianto=='pm'?'selected':''?> >PM</option>
            </select>
        </div>
    </div>                

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	</div>
</div>
