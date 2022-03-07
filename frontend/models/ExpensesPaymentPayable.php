<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expenses_payment_payable".
 *
 * @property int $id
 * @property int $expense_id
 * @property double $amount_paid
 * @property string $jev
 * @property string $transaction_date
 *
 * @property Expenses $expense
 */
class ExpensesPaymentPayable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses_payment_payable';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expense_id', 'amount_paid', 'transaction_date'], 'required'],
            [['expense_id'], 'integer'],
            [['amount_paid'], 'number'],
            [['transaction_date'], 'safe'],
            [['jev'], 'string', 'max' => 300],
            [['expense_id'], 'exist', 'skipOnError' => true, 'targetClass' => Expenses::className(), 'targetAttribute' => ['expense_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'expense_id' => 'Expense ID',
            'amount_paid' => 'Amount Paid',
            'jev' => 'Jev',
            'transaction_date' => 'Transaction Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpense()
    {
        return $this->hasOne(Expenses::className(), ['id' => 'expense_id']);
    }
}
