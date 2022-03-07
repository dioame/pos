<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_deductions_honorarium".
 *
 * @property int $id
 * @property int $pID
 * @property int $dID
 * @property double $amount
 *
 * @property PayrollHonorarium $p
 * @property PayslipDeductionTypes $d
 */
class PayrollDeductionsHonorarium extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payroll_deductions_honorarium';
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
            [['pID'], 'exist', 'skipOnError' => true, 'targetClass' => PayrollHonorarium::className(), 'targetAttribute' => ['pID' => 'id']],
            [['dID'], 'exist', 'skipOnError' => true, 'targetClass' => PayslipDeductionTypes::className(), 'targetAttribute' => ['dID' => 'id']],
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
        return $this->hasOne(PayrollHonorarium::className(), ['id' => 'pID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getD()
    {
        return $this->hasOne(PayslipDeductionTypes::className(), ['id' => 'dID']);
    }
}
