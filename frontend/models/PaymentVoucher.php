<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_voucher".
 *
 * @property int $id
 * @property string $dv_number
 * @property string $description
 * @property int $account
 * @property double $quantity
 * @property double $unit_price
 * @property double $discount
 * @property double $tax
 */
class PaymentVoucher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_voucher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dv_number', 'description', 'account', 'quantity', 'unit_price'], 'required'],
            [['account'], 'integer'],
            [['quantity', 'unit_price', 'discount', 'tax'], 'number'],
            [['dv_number'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dv_number' => 'Dv Number',
            'description' => 'Description',
            'account' => 'Account',
            'quantity' => 'Quantity',
            'unit_price' => 'Unit Price',
            'discount' => 'Discount',
            'tax' => 'Tax',
        ];
    }
}
