<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "inventory_loss".
 *
 * @property int $id
 * @property string $date_posted
 * @property int $quantity
 */
class InventoryLoss extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'inventory_loss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_posted', 'pID', 'quantity'], 'required'],
            [['date_posted'], 'safe'],
            [['quantity'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_posted' => 'Date Posted',
            'pID' => 'Product Name',
            'quantity' => 'Quantity',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['id' => 'pID']);
    }
}
