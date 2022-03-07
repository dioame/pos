<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accounts_payable_payment".
 *
 * @property int $id
 * @property int $ap_id
 * @property string $dv_number
 *
 * @property AccountsPayableInvoice $ap
 * @property DvTracking $dvNumber
 */
class AccountsPayablePayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounts_payable_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ap_id'], 'required'],
            [['ap_id'], 'integer'],
            [['dv_number','pcv_number'], 'string', 'max' => 30],
            [['ap_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccountsPayableInvoice::className(), 'targetAttribute' => ['ap_id' => 'id']],
            [['dv_number'], 'exist', 'skipOnError' => true, 'targetClass' => DvTracking::className(), 'targetAttribute' => ['dv_number' => 'dv_number']],
            [['pcv_number'], 'exist', 'skipOnError' => true, 'targetClass' => PcvTracking::className(), 'targetAttribute' => ['pcv_number' => 'pcv_number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ap_id' => 'Accounts Payable Invoice',
            'dv_number' => 'DV Number',
            'pcv_number' => 'PCV Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAp()
    {
        return $this->hasOne(AccountsPayableInvoice::className(), ['id' => 'ap_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDvNumber()
    {
        return $this->hasOne(DvTracking::className(), ['dv_number' => 'dv_number']);
    }

    public function getPcvNumber()
    {
        return $this->hasOne(PcvTracking::className(), ['pcv_number' => 'pcv_number']);
    }

    
}
