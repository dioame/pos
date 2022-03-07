<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ProductCategories;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
<div class="col-md-offset-3 col-md-6 col-md-offset-3">
    <center><h1><?= Html::encode($this->title) ?></h1></center><hr>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sku')->textInput(['maxlength' => true, 'value'=>rand(10000,90000)]) ?>

    <?= $form->field($model, 'product_name')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?php
        echo $form->field($model, 'category')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(ProductCategories::find()->all(), 'id', 'category'),
            'options' => ['placeholder' => 'Select product category...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
