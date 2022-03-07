<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_honorarium".
 *
 * @property int $id
 * @property string $type
 * @property int $emp_id
 * @property string $date_from
 * @property string $date_to
 * @property double $number_of_hours
 * @property double $hourly_rate
 * @property string $date_created
 * @property string $dv
 * @property string $jev
 *
 * @property Officers $emp
 */
class PayrollHonorarium extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payroll_honorarium';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'date_from', 'date_to', 'number_of_hours', 'hourly_rate'], 'required'],
            [['emp_id'], 'integer'],
            [['date_from', 'date_to', 'date_created'], 'safe'],
            [['number_of_hours', 'hourly_rate'], 'number'],
            [['dv'], 'string', 'max' => 30],
            [['jev'], 'string', 'max' => 300],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Officers::className(), 'targetAttribute' => ['emp_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'emp_id' => 'Emp ID',
            'date_from' => 'Date From',
            'date_to' => 'Date To',
            'number_of_hours' => 'Number Of Hours',
            'hourly_rate' => 'Hourly Rate',
            'date_created' => 'Date Created',
            'dv' => 'Dv',
            'jev' => 'Jev',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmp()
    {
        return $this->hasOne(Officers::className(), ['id' => 'emp_id']);
    }
}
