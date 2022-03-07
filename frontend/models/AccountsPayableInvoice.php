<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accounts_payable_invoice".
 *
 * @property int $id
 * @property string $invoice_number
 * @property int $supplier
 * @property string $invoice_date
 * @property string $due_date
 * @property int $po_number
 * @property int $type_of_expense
 * @property double $amount
 * @property string $jev
 *
 * @property Uacs $typeOfExpense
 * @property Suppliers $supplier0
 */
class AccountsPayableInvoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounts_payable_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier', 'invoice_date', 'due_date', 'type_of_expense', 'amount'], 'required'],
            [['supplier', 'po_number', 'type_of_expense'], 'integer'],
            [['invoice_date', 'due_date'], 'safe'],
            [['amount'], 'number'],
            [['invoice_number'], 'string', 'max' => 50],
            [['jev'], 'string', 'max' => 30],
            [['type_of_expense'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['type_of_expense' => 'uacs']],
            [['supplier'], 'exist', 'skipOnError' => true, 'targetClass' => Suppliers::className(), 'targetAttribute' => ['supplier' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_number' => 'Invoice #',
            'supplier' => 'Supplier',
            'invoice_date' => 'Invoice Date',
            'due_date' => 'Due Date',
            'po_number' => 'PO #',
            'type_of_expense' => 'Type Of Expense',
            'amount' => 'Amount',
            'jev' => 'Jev',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeOfExpense()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'type_of_expense']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier0()
    {
        return $this->hasOne(Suppliers::className(), ['id' => 'supplier']);
    }

    public function getAllpayable(){
        return '#'.$this->invoice_number.' - '.$this->supplier0->supplier_name.' ('.number_format($this->amount,2).')';
    }
}
