<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expenses_payments".
 *
 * @property int $id
 * @property int $expense_id
 * @property string $type
 * @property double $amount_paid
 * @property string $date
 *
 * @property Expenses $expense
 */
class ExpensesPayments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expenses_payments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expense_id', 'type', 'amount_paid', 'date_recorded'], 'required'],
            [['expense_id'], 'integer'],
            [['type'], 'string'],
            [['amount_paid'], 'number'],
            [['date_recorded'], 'safe'],
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
            'type' => 'Type',
            'amount_paid' => 'Amount Paid',
            'date_recorded' => 'Date',
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
