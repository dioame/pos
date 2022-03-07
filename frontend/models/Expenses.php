<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expenses".
 *
 * @property int $id
 * @property string $date
 * @property int $type
 * @property double $quantity
 * @property string $unit
 * @property double $unit_cost
 * @property double $amount
 * @property double $amount_paid
 * @property double $balance
 * @property int $supplier
 * @property string $jev
 * @property string $dv
 *
 * @property Uacs $type0
 * @property Suppliers $supplier0
 * @property JevTracking $jev0
 * @property DvTracking $dv0
 * @property ExpensesPaymentPayable[] $expensesPaymentPayables
 * @property ExpensesPayments[] $expensesPayments
 */
class Expenses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $remarks;
    public static function tableName()
    {
        return 'expenses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'type', 'quantity', 'unit_cost', 'amount', 'amount_paid', 'balance'], 'required'],
            [['date','remarks', 'unit'], 'safe'],
            [['type', 'supplier'], 'integer'],
            [['quantity', 'unit_cost', 'amount', 'amount_paid', 'balance'], 'number'],
            [['unit', 'dv'], 'string', 'max' => 30],
            [['jev'], 'string', 'max' => 300],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => Uacs::className(), 'targetAttribute' => ['type' => 'uacs']],
            [['supplier'], 'exist', 'skipOnError' => true, 'targetClass' => Suppliers::className(), 'targetAttribute' => ['supplier' => 'id']],
            [['jev'], 'exist', 'skipOnError' => true, 'targetClass' => JevTracking::className(), 'targetAttribute' => ['jev' => 'jev']],
            [['dv'], 'exist', 'skipOnError' => true, 'targetClass' => DvTracking::className(), 'targetAttribute' => ['dv' => 'dv_number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'type' => 'Type',
            'quantity' => 'Quantity',
            'unit' => 'Unit',
            'unit_cost' => 'Unit Cost',
            'amount' => 'Amount',
            'amount_paid' => 'Amount Paid',
            'balance' => 'Balance',
            'supplier' => 'Supplier',
            'jev' => 'Jev',
            'dv' => 'Dv',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType0()
    {
        return $this->hasOne(Uacs::className(), ['uacs' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier0()
    {
        return $this->hasOne(Suppliers::className(), ['id' => 'supplier']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJev0()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDv0()
    {
        return $this->hasOne(DvTracking::className(), ['dv_number' => 'dv']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpensesPaymentPayables()
    {
        return $this->hasMany(ExpensesPaymentPayable::className(), ['expense_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpensesPayments()
    {
        return $this->hasMany(ExpensesPayments::className(), ['expense_id' => 'id']);
    }
}
