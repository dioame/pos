<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "petty_cash".
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
 */
class PettyCash extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $remarks;
    public static function tableName()
    {
        return 'petty_cash';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'type', 'quantity', 'unit_cost', 'amount', 'amount_paid', 'balance'], 'required'],
            [['date','remarks'], 'safe'],
            [['type', 'supplier'], 'integer'],
            [['quantity', 'unit_cost', 'amount', 'amount_paid', 'balance'], 'number'],
            [['unit', 'dv'], 'string', 'max' => 30],
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
}
