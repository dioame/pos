<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchase_details".
 *
 * @property int $id
 * @property int $purchase_id
 * @property int $product_id
 * @property double $quantity
 * @property string $unit
 * @property double $cost
 *
 * @property Purchases $purchase
 * @property Products $product
 */
class PurchaseDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'cost', 'total'], 'required'],
            [['purchase_id', 'product_id'], 'integer'],
            [['total'], 'safe'],
            [['quantity', 'cost', 'total'], 'number'],
            [['unit'], 'string', 'max' => 30],
            //[['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchases::className(), 'targetAttribute' => ['purchase_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_id' => 'Purchase ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'unit' => 'Unit',
            'cost' => 'Cost',
            'total' => 'Total',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchases::className(), ['id' => 'purchase_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'product_id']);
    }
}
