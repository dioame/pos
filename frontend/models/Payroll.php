<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll".
 *
 * @property int $id
 * @property int $emp_id
 * @property string $date_from
 * @property string $date_to
 * @property double $number_of_hours
 * @property double $hourly_rate
 *
 * @property Employees $emp
 * @property PayrollDeductions[] $payrollDeductions
 */
class Payroll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payroll';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'date_from', 'date_to', 'number_of_hours', 'hourly_rate'], 'required'],
            [['emp_id'], 'integer'],
            [['date_from', 'date_to', 'jev'], 'safe'],
            [['number_of_hours', 'hourly_rate'], 'number'],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::className(), 'targetAttribute' => ['emp_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emp_id' => 'Emp ID',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'number_of_hours' => 'Duration',
            'hourly_rate' => 'Rate',
            'jev' => 'JEV',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmp()
    {
        return $this->hasOne(Employees::className(), ['id' => 'emp_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPayrollDeductions()
    {
        return $this->hasMany(PayrollDeductions::className(), ['pID' => 'id']);
    }
}
