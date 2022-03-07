<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments_receivable".
 *
 * @property int $id
 * @property int $sales_id
 * @property string $transaction_date
 * @property double $amount_paid
 * @property string $jev
 * @property int $orNo
 *
 * @property Sales $sales
 */
class PaymentsReceivable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments_receivable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sales_id', 'transaction_date', 'amount_paid'], 'required'],
            [['sales_id', 'orNo'], 'integer'],
            [['transaction_date'], 'safe'],
            [['amount_paid'], 'number'],
            [['jev'], 'string', 'max' => 300],
            [['sales_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sales::className(), 'targetAttribute' => ['sales_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sales_id' => 'Sales ID',
            'transaction_date' => 'Transaction Date',
            'amount_paid' => 'Amount Paid',
            'jev' => 'Jev',
            'orNo' => 'Or No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasOne(Sales::className(), ['id' => 'sales_id']);
    }

    public function getOr()
    {
        return $this->hasOne(OrArTracking::className(), ['id' => 'orNo']);
    }
}
