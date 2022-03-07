<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payroll_dividends".
 *
 * @property int $id
 * @property int $emp_id
 * @property string $date_from
 * @property string $date_to
 * @property double $number_of_hours
 * @property double $hourly_rate
 * @property string $date_created
 * @property string $dv
 * @property string $pcv
 * @property string $jev
 *
 * @property Members $emp
 * @property DvTracking $dv0
 * @property PcvTracking $pcv0
 * @property JevTracking $jev0
 */
class PayrollDividends extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payroll_dividends';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id', 'date_from', 'date_to', 'number_of_hours', 'hourly_rate'], 'required'],
            [['emp_id'], 'integer'],
            [['date_from', 'date_to', 'date_created','dividends_payable_jev'], 'safe'],
            [['number_of_hours', 'hourly_rate'], 'number'],
            [['dv', 'pcv'], 'string', 'max' => 30],
            [['jev'], 'string', 'max' => 300],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Members::className(), 'targetAttribute' => ['emp_id' => 'id']],
            [['dv'], 'exist', 'skipOnError' => true, 'targetClass' => DvTracking::className(), 'targetAttribute' => ['dv' => 'dv_number']],
            [['pcv'], 'exist', 'skipOnError' => true, 'targetClass' => PcvTracking::className(), 'targetAttribute' => ['pcv' => 'pcv_number']],
            [['jev'], 'exist', 'skipOnError' => true, 'targetClass' => JevTracking::className(), 'targetAttribute' => ['jev' => 'jev']],
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
            'number_of_hours' => 'Number Of Hours',
            'hourly_rate' => 'Hourly Rate',
            'date_created' => 'Date Created',
            'dv' => 'Dv',
            'pcv' => 'Pcv',
            'jev' => 'Jev',
            'dividends_payable_jev' => 'Dividends Payable Jev',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmp()
    {
        return $this->hasOne(Members::className(), ['id' => 'emp_id']);
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
    public function getPcv0()
    {
        return $this->hasOne(PcvTracking::className(), ['pcv_number' => 'pcv']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJev0()
    {
        return $this->hasOne(JevTracking::className(), ['jev' => 'jev']);
    }
}
