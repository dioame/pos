<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_card".
 *
 * @property int $id
 * @property string $sku
 * @property double $existing
 * @property double $added
 * @property double $total
 */
class StockCard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'added', 'date', 'finished','remarks'], 'required'],
            [['existing', 'added', 'total'], 'number'],
            [['sku'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'Product',
            'existing' => 'Existing',
            'added' => 'Added',
            'total' => 'Total',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['sku' => 'sku']);
    }

    public function getTotal($month){
        $stocks = $month==0?StockCard::find()->all():StockCard::find()->where(['MONTH(date)'=>$month])->all();
        $sum = 0;
        foreach ($stocks as $stock) {
            $sum += $stock->price*$stock->added;
        }
        return number_format($sum,2);
    }
}
