<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_categories".
 *
 * @property int $id
 * @property string $category
 * @property int $salesAccount
 * @property int $purchaseAccount
 *
 * @property Uacs $salesAccount0
 * @property Uacs $purchaseAccount0
 * @property Products[] $products
 */
class ProductCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'salesAccount', 'purchaseAccount'], 'required'],
            [['salesAccount', 'purchaseAccount'], 'integer'],
            [['category'], 'string', 'max' => 300],
            [['salesAccount'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['salesAccount' => 'uacs']],
            [['purchaseAccount'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['purchaseAccount' => 'uacs']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'salesAccount' => 'Sales Account',
            'purchaseAccount' => 'Purchase Account',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesAccount0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'salesAccount']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseAccount0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'purchaseAccount']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['category' => 'id']);
    }
}
