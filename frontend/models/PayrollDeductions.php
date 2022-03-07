<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_deductions".
 *
 * @property int $id
 * @property int $pID
 * @property int $dID
 * @property double $amount
 *
 * @property Payroll $p
 * @property PayrollDeductions $d
 * @property PayrollDeductions[] $payrollDeductions
 */
class PayrollDeductions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payroll_deductions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pID', 'dID', 'amount'], 'required'],
            [['pID', 'dID'], 'integer'],
            [['amount'], 'number'],
            [['pID'], 'exist', 'skipOnError' => true, 'targetClass' => Payroll::className(), 'targetAttribute' => ['pID' => 'id']],
            [['dID'], 'exist', 'skipOnError' => true, 'targetClass' => PayrollDeductions::className(), 'targetAttribute' => ['dID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pID' => 'P ID',
            'dID' => 'D ID',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(Payroll::className(), ['id' => 'pID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getD()
    {
        return $this->hasOne(PayrollDeductions::className(), ['id' => 'dID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollDeductions()
    {
        return $this->hasMany(PayrollDeductions::className(), ['dID' => 'id']);
    }
}
