<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchases".
 *
 * @property int $id
 * @property int $payee
 * @property string $date_posted
 * @property double $total
 * @property double $amount_paid
 * @property double $accounts_payable
 * @property string $dv_number
 * @property int $ap_invoice
 * @property string $jev
 * @property double $discount
 *
 * @property PurchaseDetails[] $purchaseDetails
 */
class Purchases extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payee', 'date_posted', 'total', 'amount_paid', 'accounts_payable'], 'required'],
            [['payee', 'ap_invoice'], 'integer'],
            [['date_posted'], 'safe'],
            [['total', 'amount_paid', 'accounts_payable', 'discount'], 'number'],
            [['dv_number'], 'string', 'max' => 30],
            [['jev'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payee' => 'Payee',
            'date_posted' => 'Date Posted',
            'total' => 'Total',
            'amount_paid' => 'Amount Paid',
            'accounts_payable' => 'Accounts Payable',
            'dv_number' => 'Dv Number',
            'ap_invoice' => 'Ap Invoice',
            'jev' => 'Jev',
            'discount' => 'Discount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseDetails()
    {
        return $this->hasMany(PurchaseDetails::className(), ['purchase_id' => 'id']);
    }
}
