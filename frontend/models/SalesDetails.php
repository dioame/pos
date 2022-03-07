<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sales_details".
 *
 * @property int $id
 * @property int $sales_id
 * @property int $product_id
 * @property double $quantity
 * @property double $product_price
 * @property double $buying_price
 * @property double $sub_total
 *
 * @property Sales $sales
 * @property Products $product
 */
class SalesDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sales_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sales_id', 'product_id', 'quantity', 'product_price', 'sub_total'], 'required'],
            [['unit'], 'safe'],
            [['sales_id', 'product_id'], 'integer'],
            [['quantity', 'product_price', 'buying_price', 'sub_total'], 'number'],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sales::className(), 'targetAttribute' => ['sales_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sales_id' => 'Sales ID',
            'product_id' => 'Product ID',
            'unit' => 'Unit',
            'quantity' => 'Quantity',
            'product_price' => 'Unit Price',
            'buying_price' => 'Buying Price',
            'sub_total' => 'Sub Total',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasOne(Sales::className(), ['id' => 'sales_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }

    /**
     * @inheritdoc
     * @return SalesDetailsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalesDetailsQuery(get_called_class());
    }

    public function getTotalSales($month){
        $sales = $month==0?SalesDetails::find()->all():SalesDetails::find()->joinWith('sales')->where(['MONTH(sales.transaction_date)'=>$month])->all();
        $sum = 0;
        foreach ($sales as $sale) {
            $sum += $sale->quantity*$sale->buying_price;
        }
        return $sum;
    }

    public function getCostToday(){
        $sales =SalesDetails::find()->joinWith('sales')->where(['date(sales.transaction_date)'=>date('Y-m-d')])->all();
        $sum = 0;
        foreach ($sales as $sale) {
            $sum += $sale->quantity*$sale->buying_price;
        }
        return $sum;
    }
}
