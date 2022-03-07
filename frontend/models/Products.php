<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $sku
 * @property string $product_name
 * @property double $quantity_at_hand
 * @property double $buying_price
 * @property double $price
 * @property int $recommended_supplier
 *
 * @property SalesDetails[] $salesDetails
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $remarks,$date,$finished;
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'product_name', 'price', 'category'], 'required'],
            [['price'], 'number'],
            [['sku', 'product_name'], 'string', 'max' => 300],
            [['sku'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'Product ID',
            'product_name' => 'Product Name',
            'price' => 'Price',
            'category' => 'Category',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSalesDetails()
    {
        return $this->hasMany(SalesDetails::className(), ['product_id' => 'id']);
    }

    public function getCategory0()
    {
        return $this->hasOne(ProductCategories::className(), ['id' => 'category']);
    }

    /**
     * {@inheritdoc}
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsQuery(get_called_class());
    }
}
