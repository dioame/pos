<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ProductCategories */

$this->title = 'Create New Product Category';
$this->params['breadcrumbs'][] = ['label' => 'Product Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-categories-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
