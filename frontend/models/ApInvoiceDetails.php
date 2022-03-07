<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ap_invoice_details".
 *
 * @property int $id
 * @property int $ap_id
 * @property int $account_code
 * @property double $amount
 *
 * @property Uacs $accountCode
 * @property AccountsPayableInvoice $ap
 */
class ApInvoiceDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ap_invoice_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ap_id', 'account_code', 'amount'], 'required'],
            [['ap_id', 'account_code'], 'integer'],
            [['amount'], 'number'],
            [['account_code'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['account_code' => 'uacs']],
            [['ap_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountsPayableInvoice::className(), 'targetAttribute' => ['ap_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ap_id' => 'Ap ID',
            'account_code' => 'Account Code',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccountCode()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'account_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAp()
    {
        return $this->hasOne(AccountsPayableInvoice::className(), ['id' => 'ap_id']);
    }
}
