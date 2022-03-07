<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Pricelist;

/* @var $this yii\web\View */
/* @var $model app\models\Products */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['masterlist']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    td{
        padding: 10px;
    }
</style>
<div class="row">

    <div class="col-md-6">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Add New Price', ['pricelist/create', 'pID' => $model->id], [
            'class' => 'btn btn-info',
            'data' => [
                'confirm' => 'Are you sure you want to add new price?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'sku',
            'product_name',
            //'quantity_at_hand',
            //'buying_price',
            //'price',
            //'recommended_supplier',
        ],
    ]) ?>

    <br>
    <h3>Price History</h3>
    <div style="height:400px;overflow-y: scroll;">
        <table width="100%" bord>
        <?php
            $prices = Pricelist::find()->where(['pID'=>$model->id])->orderBy(['id'=>SORT_DESC])->all();
            foreach ($prices as $key) {
                echo '<tr><td>'.date('M j, Y',strtotime($key->date_adjusted)).'</td><td style="text-align:right;">'.number_format($key->price,2).'</td></tr>';
            }
        ?>
        </table>
    </div>

    </div>

</div>
