<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pricelist".
 *
 * @property int $id
 * @property string $date_adjusted
 * @property int $pId
 * @property double $price
 *
 * @property Products $p
 */
class Pricelist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricelist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_adjusted', 'pId', 'price'], 'required'],
            [['date_adjusted'], 'safe'],
            [['pId'], 'integer'],
            [['price'], 'number'],
            [['pId'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['pId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_adjusted' => 'Date Adjusted',
            'pId' => 'Product',
            'price' => 'Price',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(Products::className(), ['id' => 'pId']);
    }
}
